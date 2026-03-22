<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        // Don't list the main super admin to prevent accidental deletion
        $employees = User::where('id', '!=', auth()->id())->latest()->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|max:50',
            'salary' => 'required|numeric|min:0',
            'biometric_id' => 'nullable|string|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => strtolower($request->role),
            'salary' => $request->salary,
            'biometric_id' => $request->biometric_id,
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee registered successfully.');
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|max:50',
            'salary' => 'required|numeric|min:0',
            'biometric_id' => ['nullable', 'string', Rule::unique('users')->ignore($employee->id)],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => strtolower($request->role),
            'salary' => $request->salary,
            'biometric_id' => $request->biometric_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(User $employee)
    {
        if ($employee->id === auth()->id()) {
            return redirect()->route('admin.employees.index')->with('error', 'Cannot delete your own account.');
        }

        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
