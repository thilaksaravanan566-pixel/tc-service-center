<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerFollowup;
use App\Models\ServiceOrder;
use App\Models\ProductOrder;
use App\Models\WarrantyCertificate;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    /**
     * Customer database with CRM filters.
     */
    public function index(Request $request)
    {
        $query = Customer::query()->withCount(['serviceOrders', 'productOrders', 'warrantyClaims']);

        if ($request->search) {
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('mobile', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->sort === 'most_orders') {
            $query->orderByDesc('service_orders_count');
        } elseif ($request->sort === 'most_purchases') {
            $query->orderByDesc('product_orders_count');
        } else {
            $query->latest();
        }

        $customers = $query->paginate(20);

        $stats = [
            'total'        => Customer::count(),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
            'with_repairs' => Customer::has('serviceOrders')->count(),
            'with_purchases' => Customer::has('productOrders')->count(),
        ];

        return view('admin.crm.index', compact('customers', 'stats'));
    }

    /**
     * 360-degree customer view.
     */
    public function show($id)
    {
        $customer = Customer::with([
            'serviceOrders.device',
            'serviceOrders.technician',
            'productOrders.sparePart',
            'warranties.sparePart',
            'warranties.serviceOrder',
            'warrantyClaims',
        ])->findOrFail($id);

        $followups = CustomerFollowup::with('creator')
            ->where('customer_id', $id)
            ->latest()
            ->get();

        $totalSpent = $customer->productOrders->where('is_paid', true)->sum('total_price');
        $totalRepairs = $customer->serviceOrders->count();

        return view('admin.crm.show', compact('customer', 'followups', 'totalSpent', 'totalRepairs'));
    }

    /**
     * Add a CRM follow-up note for a customer.
     */
    public function addFollowup(Request $request, $customerId)
    {
        $request->validate([
            'type'        => 'required|in:call,email,visit,sms',
            'notes'       => 'required|string|max:1000',
            'followup_at' => 'nullable|date',
        ]);

        CustomerFollowup::create([
            'customer_id' => $customerId,
            'created_by'  => auth()->id(),
            'type'        => $request->type,
            'notes'       => $request->notes,
            'followup_at' => $request->followup_at,
        ]);

        return back()->with('success', 'Follow-up note added successfully.');
    }
}
