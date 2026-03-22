<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = User::where('role', 'technician')->latest()->get();
        return view('admin.technicians.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.technicians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'technician',
        ]);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician registered successfully.');
    }

    public function edit(User $technician)
    {
        return view('admin.technicians.edit', compact('technician'));
    }

    public function update(Request $request, User $technician)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $technician->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $technician->update($data);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician updated successfully.');
    }

    public function destroy(User $technician)
    {
        if ($technician->serviceOrders()->count() > 0) {
            return redirect()->route('admin.technicians.index')->with('error', 'Cannot delete technician with assigned jobs.');
        }
        
        $technician->delete();
        return redirect()->route('admin.technicians.index')->with('success', 'Technician deleted successfully.');
    }
}
