<?php

namespace App\Http\Controllers;

use App\Models\StationaryItem;
use App\Models\Godown;
use App\Models\Customer;
use App\Models\School;
use App\Models\Student;
use App\Models\Sale;
use App\Models\StationaryAward;
use App\Models\Procurement;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current date and calculate date ranges
        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth();
        $startOfYear = $now->startOfYear();
        $lastMonth = $now->copy()->subMonth();

        // Stock Overview
        $totalItems = StationaryItem::count();
        $activeItems = StationaryItem::active()->count();
        $lowStockItems = StationaryItem::active()->get()->filter(function($item) {
            return $item->isLowStock();
        })->count();
        $expiringItems = StationaryItem::expiringSoon(30)->count();

        // Financial Overview
        $totalStockValue = StationaryItem::active()->get()->sum(function($item) {
            return $item->current_stock * $item->unit_cost;
        });

        $monthlySales = Sale::completed()
            ->whereBetween('sale_date', [$startOfMonth, $now])
            ->sum('total_amount');

        $monthlyDonations = Sale::completed()
            ->whereBetween('sale_date', [$startOfMonth, $now])
            ->sum('donation_amount');

        $monthlyProfit = Sale::completed()
            ->whereBetween('sale_date', [$startOfMonth, $now])
            ->sum('profit_amount');

        // Customer Overview
        $totalCustomers = Customer::active()->count();
        $newCustomersThisMonth = Customer::whereBetween('membership_date', [$startOfMonth, $now])->count();

        // School and Student Overview
        $totalSchools = School::active()->count();
        $totalStudents = Student::active()->count();
        $newStudentsThisMonth = Student::whereBetween('enrollment_date', [$startOfMonth, $now])->count();

        // Godown Overview
        $totalGodowns = Godown::active()->count();

        // User Overview
        $totalUsers = User::count();

        // Award Overview
        $monthlyAwards = StationaryAward::awarded()
            ->whereBetween('award_date', [$startOfMonth, $now])
            ->sum('quantity');

        $totalAwardsDistributed = StationaryAward::awarded()->sum('quantity');

        $monthlyAwardValue = StationaryAward::awarded()
            ->whereBetween('award_date', [$startOfMonth, $now])
            ->sum('total_cost');

        // Procurement Overview
        $monthlyProcurements = Procurement::received()
            ->whereBetween('procurement_date', [$startOfMonth, $now])
            ->sum('quantity');

        $monthlyProcurementValue = Procurement::received()
            ->whereBetween('procurement_date', [$startOfMonth, $now])
            ->sum('total_cost');

        // Recent Activities
        $recentSales = Sale::with(['customer', 'stationaryItem'])
            ->orderBy('sale_date', 'desc')
            ->limit(5)
            ->get();

        $recentAwards = StationaryAward::with(['student', 'school', 'stationaryItem'])
            ->orderBy('award_date', 'desc')
            ->limit(5)
            ->get();

        $recentProcurements = Procurement::with(['stationaryItem', 'godown'])
            ->orderBy('procurement_date', 'desc')
            ->limit(5)
            ->get();

        // Low Stock Alerts
        $lowStockAlerts = StationaryItem::active()->get()->filter(function($item) {
            return $item->isLowStock();
        })->take(5);

        // Expiring Items Alerts
        $expiringAlerts = StationaryItem::expiringSoon(30)->take(5)->get();

        // Top Selling Items
        $topSellingItems = Sale::with('stationaryItem')
            ->selectRaw('stationary_item_id, SUM(quantity) as total_sold')
            ->whereBetween('sale_date', [$startOfMonth, $now])
            ->groupBy('stationary_item_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Top Awarded Items
        $topAwardedItems = StationaryAward::with('stationaryItem')
            ->selectRaw('stationary_item_id, SUM(quantity) as total_awarded')
            ->whereBetween('award_date', [$startOfMonth, $now])
            ->groupBy('stationary_item_id')
            ->orderBy('total_awarded', 'desc')
            ->limit(5)
            ->get();

        // Godown Status
        $godownStatus = Godown::active()->withCount(['procurements', 'sales', 'stationaryAwards'])->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalItems' => $totalItems,
                'activeItems' => $activeItems,
                'lowStockItems' => $lowStockItems,
                'expiringItems' => $expiringItems,
                'totalStockValue' => $totalStockValue,
                'monthlySales' => $monthlySales,
                'monthlyDonations' => $monthlyDonations,
                'monthlyProfit' => $monthlyProfit,
                'totalCustomers' => $totalCustomers,
                'newCustomersThisMonth' => $newCustomersThisMonth,
                'totalSchools' => $totalSchools,
                'totalStudents' => $totalStudents,
                'newStudentsThisMonth' => $newStudentsThisMonth,
                'totalGodowns' => $totalGodowns,
                'totalUsers' => $totalUsers,
                'monthlyAwards' => $monthlyAwards,
                'totalAwardsDistributed' => $totalAwardsDistributed,
                'monthlyAwardValue' => $monthlyAwardValue,
                'monthlyProcurements' => $monthlyProcurements,
                'monthlyProcurementValue' => $monthlyProcurementValue,
            ],
            'recentActivities' => [
                'sales' => $recentSales,
                'awards' => $recentAwards,
                'procurements' => $recentProcurements,
            ],
            'alerts' => [
                'lowStock' => $lowStockAlerts,
                'expiring' => $expiringAlerts,
            ],
            'topItems' => [
                'selling' => $topSellingItems,
                'awarded' => $topAwardedItems,
            ],
            'godownStatus' => $godownStatus,
        ]);
    }

    public function stockReport()
    {
        $items = StationaryItem::with(['procurements', 'sales', 'stationaryAwards'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'company' => $item->company,
                    'current_stock' => $item->current_stock,
                    'unit_cost' => $item->unit_cost,
                    'total_value' => $item->current_stock * $item->unit_cost,
                    'min_stock_level' => $item->min_stock_level,
                    'is_low_stock' => $item->isLowStock(),
                    'expiry_date' => $item->expiry_date,
                    'is_expired' => $item->isExpired(),
                ];
            });

        return Inertia::render('Reports/StockReport', [
            'items' => $items,
        ]);
    }

    public function salesReport(Request $request)
    {
        $query = Sale::with(['customer', 'stationaryItem', 'godown']);

        if ($request->filled('start_date')) {
            $query->where('sale_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('sale_date', '<=', $request->end_date);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(20);

        return Inertia::render('Reports/SalesReport', [
            'sales' => $sales,
            'filters' => $request->only(['start_date', 'end_date', 'payment_type']),
        ]);
    }

    public function awardsReport(Request $request)
    {
        $query = StationaryAward::with(['student', 'school', 'stationaryItem', 'godown']);

        if ($request->filled('start_date')) {
            $query->where('award_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('award_date', '<=', $request->end_date);
        }

        if ($request->filled('award_type')) {
            $query->where('award_type', $request->award_type);
        }

        $awards = $query->orderBy('award_date', 'desc')->paginate(20);

        return Inertia::render('Reports/AwardsReport', [
            'awards' => $awards,
            'filters' => $request->only(['start_date', 'end_date', 'award_type']),
        ]);
    }
}
