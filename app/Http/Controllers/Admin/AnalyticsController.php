<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\ProductOrder;
use App\Models\Customer;
use App\Models\SparePart;
use App\Models\Billing;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $currentYear  = now()->year;
        $currentMonth = now()->month;

        // ─── Core KPIs ───
        $stats = [
            'total_customers'     => Customer::count(),
            'new_customers_month' => Customer::whereMonth('created_at', $currentMonth)->count(),
            'total_repairs'       => ServiceOrder::count(),
            'repairs_this_month'  => ServiceOrder::whereMonth('created_at', $currentMonth)->count(),
            'total_product_orders'=> ProductOrder::count(),
            'pending_repairs'     => ServiceOrder::whereIn('status', ['received', 'diagnosing', 'in_progress'])->count(),
            'completed_repairs'   => ServiceOrder::where('status', 'completed')->count(),
            'total_technicians'   => User::where('role', 'technician')->count(),
            'low_stock_parts'     => SparePart::where('stock', '<', 5)->count(),
        ];

        // ─── Revenue KPIs ───
        $stats['monthly_revenue'] = Billing::where('status', 'Paid')
            ->whereMonth('invoice_date', $currentMonth)
            ->sum('amount');
        $stats['monthly_revenue'] += ProductOrder::where('is_paid', true)
            ->whereMonth('created_at', $currentMonth)
            ->sum('total_price');

        $stats['yearly_revenue'] = Billing::where('status', 'Paid')
            ->whereYear('invoice_date', $currentYear)
            ->sum('amount');
        $stats['yearly_revenue'] += ProductOrder::where('is_paid', true)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        // ─── Top Selling Products ───
        $topProducts = SparePart::withCount(['productOrders as orders_count'])
            ->withSum('productOrders as total_revenue', 'total_price')
            ->orderByDesc('orders_count')
            ->take(10)
            ->get();

        // ─── Technician Performance ───
        $technicianPerformance = User::where('role', 'technician')
            ->withCount(['serviceOrders as total_jobs',
                'serviceOrders as completed_jobs' => fn($q) => $q->where('status', 'completed')
            ])
            ->orderByDesc('completed_jobs')
            ->get();

        // ─── Monthly Sales Chart ───
        $months      = [];
        $salesData   = [];
        $repairData  = [];
        $customerData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $months[] = $date->format('M');

            $productSales = ProductOrder::where('is_paid', true)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
            $billingRevenue = Billing::where('status', 'Paid')
                ->whereYear('invoice_date', $date->year)
                ->whereMonth('invoice_date', $date->month)
                ->sum('amount');

            $salesData[]    = round($productSales + $billingRevenue, 2);
            $repairData[]   = ServiceOrder::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
            $customerData[] = Customer::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)->count();
        }

        // ─── Service Status Breakdown ───
        $serviceStatusBreakdown = ServiceOrder::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // ─── Device Type Breakdown ───
        $deviceTypes = \App\Models\Device::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->orderByDesc('count')
            ->get();

        return view('admin.analytics.dashboard', compact(
            'stats', 'topProducts', 'technicianPerformance',
            'months', 'salesData', 'repairData', 'customerData',
            'serviceStatusBreakdown', 'deviceTypes'
        ));
    }

    /**
     * JSON endpoint for chart data (AJAX refresh).
     */
    public function data(Request $request)
    {
        $type   = $request->input('type', 'monthly_revenue');
        $months = [];
        $data   = [];

        for ($i = 11; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $months[] = $date->format('M Y');

            if ($type === 'monthly_revenue') {
                $val = Billing::where('status', 'Paid')
                    ->whereYear('invoice_date', $date->year)
                    ->whereMonth('invoice_date', $date->month)
                    ->sum('amount');
                $val += ProductOrder::where('is_paid', true)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_price');
            } elseif ($type === 'new_customers') {
                $val = Customer::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count();
            } else {
                $val = ServiceOrder::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count();
            }
            $data[] = round($val, 2);
        }

        return response()->json(compact('months', 'data'));
    }
}
