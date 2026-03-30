<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function index(Request $request)
    {
        $dealers = \App\Models\Dealer::with(['user', 'user.serviceOrders'])->latest()->paginate(15);
        return view('admin.dealers.index', compact('dealers'));
    }

    public function create()
    {
        return view('admin.dealers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                /** @var \App\Models\User $user */
                $user = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                    'role' => 'dealer',
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'salary' => 0,
                ]);

                \App\Models\Dealer::create([
                    'user_id' => $user->id,
                    'business_name' => $request->business_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'gst_number' => $request->gst_number,
                    'status' => 'active',
                ]);
            });

            return redirect()->route('admin.dealers.index')->with('success', 'Dealer created successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Error creating dealer: ' . $e->getMessage());
        }
    }

    public function show(\App\Models\Dealer $dealer)
    {
        $dealer->load(['user', 'user.serviceOrders', 'user.serviceOrders.invoices']);
        return view('admin.dealers.show', compact('dealer'));
    }

    public function edit(\App\Models\Dealer $dealer)
    {
        return view('admin.dealers.edit', compact('dealer'));
    }

    public function update(Request $request, \App\Models\Dealer $dealer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dealer->user_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $dealer) {
                $user = $dealer->user;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->address = $request->address;
                if ($request->filled('password')) {
                    $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
                }
                $user->save();

                $dealer->update([
                    'business_name' => $request->business_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'gst_number' => $request->gst_number,
                    'status' => $request->status,
                ]);
            });

            return redirect()->route('admin.dealers.index')->with('success', 'Dealer updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Error updating dealer: ' . $e->getMessage());
        }
    }

    public function destroy(\App\Models\Dealer $dealer)
    {
        // Instead of hard deleting, we'll deactivate the dealer
        $dealer->update(['status' => 'inactive']);
        return back()->with('success', 'Dealer deactivated successfully.');
    }
}
