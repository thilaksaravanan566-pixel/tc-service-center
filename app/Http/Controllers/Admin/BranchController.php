<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['manager'])
            ->withCount(['serviceOrders', 'productOrders', 'employees'])
            ->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $managers = User::where('role', 'admin')->orWhere('role', 'manager')->get();
        return view('admin.branches.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:branches',
            'address'    => 'nullable|string|max:500',
            'city'       => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Branch::create($request->only(['name', 'address', 'city', 'phone', 'email', 'manager_id', 'notes']));

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        $managers = User::where('role', 'admin')->orWhere('role', 'manager')->get();
        return view('admin.branches.edit', compact('branch', 'managers'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:branches,name,' . $branch->id,
            'address'    => 'nullable|string|max:500',
            'city'       => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'is_active'  => 'boolean',
        ]);

        $branch->update($request->only(['name', 'address', 'city', 'phone', 'email', 'manager_id', 'notes', 'is_active']));

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function show(Branch $branch)
    {
        $branch->load(['manager', 'employees', 'serviceOrders', 'productOrders']);
        return view('admin.branches.show', compact('branch'));
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted.');
    }
}
