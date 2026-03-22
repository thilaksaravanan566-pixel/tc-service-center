<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $billings = Billing::latest()->get();
        return view('admin.billings.index', compact('billings'));
    }

    public function create()
    {
        return view('admin.billings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'invoice_date' => 'nullable|date',
        ]);

        Billing::create($request->all());

        return redirect()->route('admin.billings.index')->with('success', 'Bill created successfully.');
    }

    public function edit(Billing $billing)
    {
        return view('admin.billings.edit', compact('billing'));
    }

    public function update(Request $request, Billing $billing)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'invoice_date' => 'nullable|date',
        ]);

        $billing->update($request->all());

        return redirect()->route('admin.billings.index')->with('success', 'Bill updated successfully.');
    }

    public function destroy(Billing $billing)
    {
        $billing->delete();
        return redirect()->route('admin.billings.index')->with('success', 'Bill deleted successfully.');
    }
}
