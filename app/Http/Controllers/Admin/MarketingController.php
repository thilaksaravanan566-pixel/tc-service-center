<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingCampaign;
use App\Models\Customer;
use App\Models\CustomerNotification;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function index()
    {
        $campaigns = MarketingCampaign::with('creator')->latest()->paginate(15);
        $stats = [
            'total'    => MarketingCampaign::count(),
            'active'   => MarketingCampaign::where('is_active', true)->count(),
            'sent'     => MarketingCampaign::sum('sent_count'),
            'clicks'   => MarketingCampaign::sum('click_count'),
        ];
        return view('admin.marketing.index', compact('campaigns', 'stats'));
    }

    public function create()
    {
        return view('admin.marketing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'type'             => 'required|in:banner,email,sms,push,discount',
            'content'          => 'required|string',
            'target_audience'  => 'required|in:all,new_customers,repeat_customers',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'discount_percent' => 'nullable|numeric|min:1|max:100',
            'discount_code'    => 'nullable|string|max:50',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('marketing', 'public');
        }

        $campaign = MarketingCampaign::create([
            ...$request->only(['name', 'type', 'description', 'content', 'target_audience',
                               'start_date', 'end_date', 'discount_percent', 'discount_code']),
            'image_path' => $imagePath,
            'is_active'  => true,
            'created_by' => auth()->id(),
        ]);

        // If push type, auto-send notification to target customers
        if ($request->type === 'push') {
            $this->sendPushNotifications($campaign);
        }

        return redirect()->route('admin.marketing.index')->with('success', 'Campaign created successfully!');
    }

    public function edit($id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        return view('admin.marketing.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        $request->validate([
            'name'    => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $campaign->update($request->only(['name', 'type', 'description', 'content', 'target_audience',
                                          'start_date', 'end_date', 'discount_percent', 'discount_code', 'is_active']));

        return redirect()->route('admin.marketing.index')->with('success', 'Campaign updated.');
    }

    public function toggle($id)
    {
        $campaign = MarketingCampaign::findOrFail($id);
        $campaign->update(['is_active' => !$campaign->is_active]);
        return back()->with('success', 'Campaign status toggled.');
    }

    public function destroy($id)
    {
        MarketingCampaign::findOrFail($id)->delete();
        return back()->with('success', 'Campaign deleted.');
    }

    private function sendPushNotifications(MarketingCampaign $campaign): void
    {
        $customers = match($campaign->target_audience) {
            'new_customers'    => Customer::has('serviceOrders', '=', 0)->get(),
            'repeat_customers' => Customer::has('serviceOrders', '>=', 2)->get(),
            default             => Customer::all(),
        };

        foreach ($customers as $customer) {
            CustomerNotification::create([
                'customer_id' => $customer->id,
                'type'        => 'promotion',
                'title'       => '🎉 ' . $campaign->name,
                'message'     => $campaign->content,
                'icon'        => '🎁',
            ]);
        }

        $campaign->increment('sent_count', $customers->count());
    }
}
