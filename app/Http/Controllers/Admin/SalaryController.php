<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $employees = User::whereNotIn('role', ['customer'])
            ->where('id', '!=', auth()->id())
            ->get();

        // Load salary records for the selected month/year
        $salaryRecords = SalaryPayment::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('user_id');

        $stats = [
            'total_payroll'  => $salaryRecords->sum('net_salary'),
            'paid_count'     => $salaryRecords->where('status', 'paid')->count(),
            'pending_count'  => $salaryRecords->where('status', 'pending')->count(),
            'employee_count' => $employees->count(),
        ];

        return view('admin.hrm.payroll', compact('employees', 'salaryRecords', 'month', 'year', 'stats'));
    }

    /**
     * Generate salary records for all employees in a given month.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|min:2020',
        ]);

        $employees = User::whereNotIn('role', ['customer'])->get();
        $created = 0;

        foreach ($employees as $emp) {
            SalaryPayment::firstOrCreate(
                ['user_id' => $emp->id, 'month' => $request->month, 'year' => $request->year],
                [
                    'base_salary' => $emp->salary ?? 0,
                    'bonus'       => 0,
                    'deductions'  => 0,
                    'status'      => 'pending',
                ]
            );
            $created++;
        }

        return back()->with('success', "Salary records generated for {$created} employees.");
    }

    /**
     * Mark a salary payment as paid.
     */
    public function markPaid(Request $request, $id)
    {
        $request->validate([
            'bonus'      => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $record = SalaryPayment::findOrFail($id);
        $record->update([
            'bonus'      => $request->bonus ?? $record->bonus,
            'deductions' => $request->deductions ?? $record->deductions,
            'status'     => 'paid',
            'paid_at'    => now(),
            'paid_by'    => auth()->id(),
        ]);

        return back()->with('success', "Salary marked as paid for {$record->employee->name}.");
    }
}
