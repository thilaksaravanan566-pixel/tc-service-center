<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DeliveryPartnerController extends Controller
{
    public function index()
    {
        $partners = User::whereIn('role', ['delivery_partner', 'delivery'])->latest()->paginate(15);
        return view('admin.delivery_partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.delivery_partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'mobile' => 'nullable|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'delivery_partner',
            'mobile' => $request->mobile,
            'vehicle_number' => $request->vehicle_number,
        ]);

        return redirect()->route('admin.delivery-partners.index')->with('success', 'Delivery Partner added successfully.');
    }

    public function edit($id)
    {
        $partner = User::findOrFail($id);
        return view('admin.delivery_partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $partner = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($partner->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'mobile' => 'nullable|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'vehicle_number' => $request->vehicle_number,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $partner->update($data);

        return redirect()->route('admin.delivery-partners.index')->with('success', 'Delivery Partner updated successfully.');
    }

    public function destroy($id)
    {
        $partner = User::findOrFail($id);
        $partner->delete();
        return redirect()->route('admin.delivery-partners.index')->with('success', 'Delivery Partner removed successfully.');
    }
}
