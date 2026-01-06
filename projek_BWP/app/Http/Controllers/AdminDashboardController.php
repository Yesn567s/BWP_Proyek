<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalRevenue' => (float) DB::table('orders')->sum('total_price'),
            'totalOrders'  => (int) DB::table('orders')->count(),
            'totalUsers'   => (int) DB::table('users')->count(),
            'totalProducts'=> (int) DB::table('ticket_products')->count(),
        ];

        $startDate = now()->subDays(6)->startOfDay();

        $revenueTrend = DB::table('orders')
            ->selectRaw('DATE(order_date) as day, SUM(total_price) as total')
            ->where('order_date', '>=', $startDate)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $categoryBreakdown = DB::table('order_items as oi')
            ->join('ticket_products as tp', 'tp.product_id', '=', 'oi.product_id')
            ->leftJoin('ticket_categories as tc', 'tc.category_id', '=', 'tp.category_id')
            ->selectRaw('COALESCE(tc.category_name, "Uncategorized") as category, SUM(oi.price) as revenue, COUNT(*) as units')
            ->groupBy('category')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $topProducts = DB::table('order_items as oi')
            ->join('ticket_products as tp', 'tp.product_id', '=', 'oi.product_id')
            ->leftJoin('ticket_categories as tc', 'tc.category_id', '=', 'tp.category_id')
            ->selectRaw('tp.product_id as id, tp.name, COALESCE(tc.category_name, "Uncategorized") as category, SUM(oi.price) as revenue, COUNT(*) as units, MAX(tp.base_price) as price')
            ->groupBy('tp.product_id', 'tp.name', 'tc.category_name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $recentOrders = DB::table('orders as o')
            ->leftJoin('order_items as oi', 'oi.order_id', '=', 'o.order_id')
            ->selectRaw('o.order_id as id, o.order_date, o.total_price, COUNT(oi.order_item_id) as items')
            ->groupBy('o.order_id', 'o.order_date', 'o.total_price')
            ->orderByDesc('o.order_date')
            ->limit(5)
            ->get();

        $topMovies = DB::table('order_items as oi')
            ->join('ticket_products as tp', 'tp.product_id', '=', 'oi.product_id')
            ->leftJoin('ticket_categories as tc', 'tc.category_id', '=', 'tp.category_id')
            ->whereRaw('LOWER(COALESCE(tc.category_name, "")) like ?', ['%movie%'])
            ->selectRaw('tp.product_id as id, tp.name as title, COALESCE(tp.genre, tc.category_name) as genre, SUM(oi.price) as revenue')
            ->groupBy('tp.product_id', 'tp.name', 'tp.genre', 'tc.category_name')
            ->orderByDesc('revenue')
            ->limit(4)
            ->get();

        return response()->json([
            'stats'             => $stats,
            'revenueTrend'      => $revenueTrend,
            'categoryBreakdown' => $categoryBreakdown,
            'topProducts'       => $topProducts,
            'recentOrders'      => $recentOrders,
            'topMovies'         => $topMovies,
        ]);
    }
}
