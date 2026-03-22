<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Expense;
use App\Models\ProductOrder;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // ─── Revenue Sources ───
        // From service billings
        $serviceRevenue = Billing::where('status', 'Paid')
            ->whereYear('invoice_date', $currentYear)
            ->sum('amount');

        // From product orders (completed + paid)
        $productRevenue = ProductOrder::where('is_paid', true)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        $totalRevenue = $serviceRevenue + $productRevenue;

        // ─── Expenses ───
        $totalExpenses = Expense::whereYear('expense_date', $currentYear)->sum('amount');

        // ─── Net Profit ───
        $netProfit = $totalRevenue - $totalExpenses;

        // ─── Monthly Revenue Chart (last 12 months) ───
        $months       = [];
        $revenueData  = [];
        $expenseData  = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $monthRevenue = Billing::where('status', 'Paid')
                ->whereYear('invoice_date', $date->year)
                ->whereMonth('invoice_date', $date->month)
                ->sum('amount');

            $monthRevenue += ProductOrder::where('is_paid', true)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');

            $monthExpense = Expense::whereYear('expense_date', $date->year)
                ->whereMonth('expense_date', $date->month)
                ->sum('amount');

            $revenueData[]  = round($monthRevenue, 2);
            $expenseData[]  = round($monthExpense, 2);
        }

        // ─── Expenses by Category ───
        $expenseByCategory = Expense::whereYear('expense_date', $currentYear)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        // ─── This Month Stats ───
        $thisMonthRevenue = Billing::where('status', 'Paid')
            ->whereYear('invoice_date', $currentYear)
            ->whereMonth('invoice_date', $currentMonth)
            ->sum('amount');
        $thisMonthRevenue += ProductOrder::where('is_paid', true)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('total_price');

        $thisMonthExpense = Expense::whereYear('expense_date', $currentYear)
            ->whereMonth('expense_date', $currentMonth)
            ->sum('amount');

        $stats = [
            'total_revenue'      => $totalRevenue,
            'total_expenses'     => $totalExpenses,
            'net_profit'         => $netProfit,
            'profit_margin'      => $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 0,
            'this_month_revenue' => $thisMonthRevenue,
            'this_month_expense' => $thisMonthExpense,
            'this_month_profit'  => $thisMonthRevenue - $thisMonthExpense,
            'service_revenue'    => $serviceRevenue,
            'product_revenue'    => $productRevenue,
        ];

        $recentExpenses = Expense::with(['creator', 'branch'])->latest()->take(10)->get();

        return view('admin.finance.dashboard', compact(
            'stats', 'months', 'revenueData', 'expenseData',
            'expenseByCategory', 'recentExpenses'
        ));
    }

    public function expenses(Request $request)
    {
        $query = Expense::with(['creator', 'branch']);

        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->month) {
            $query->whereMonth('expense_date', $request->month);
        }
        if ($request->year) {
            $query->whereYear('expense_date', $request->year);
        }

        $expenses   = $query->latest()->paginate(20);
        $categories = Expense::categoriesForSelect();
        $totalAmount = $query->sum('amount');

        return view('admin.finance.expenses', compact('expenses', 'categories', 'totalAmount'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'category'     => 'required|string',
            'description'  => 'required|string|max:500',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_mode' => 'required|in:cash,upi,bank_transfer',
            'receipt'      => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:4096',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create([
            'category'     => $request->category,
            'description'  => $request->description,
            'amount'       => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_mode' => $request->payment_mode,
            'receipt_path' => $receiptPath,
            'notes'        => $request->notes,
            'created_by'   => auth()->id(),
            'branch_id'    => $request->branch_id,
        ]);

        return back()->with('success', 'Expense recorded successfully.');
    }

    public function destroyExpense($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('success', 'Expense deleted.');
    }

    public function reports()
    {
        $year = request('year', now()->year);

        $months = collect(range(1, 12))->map(function ($m) use ($year) {
            $revenue = Billing::where('status', 'Paid')
                ->whereYear('invoice_date', $year)
                ->whereMonth('invoice_date', $m)
                ->sum('amount');
            $revenue += ProductOrder::where('is_paid', true)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->sum('total_price');

            $expense = Expense::whereYear('expense_date', $year)
                ->whereMonth('expense_date', $m)
                ->sum('amount');

            return [
                'month'   => Carbon::createFromDate($year, $m, 1)->format('F'),
                'revenue' => $revenue,
                'expense' => $expense,
                'profit'  => $revenue - $expense,
            ];
        });

        return view('admin.finance.reports', compact('months', 'year'));
    }
}
