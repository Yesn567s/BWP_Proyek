<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 20);
        $perPage = max(5, min($perPage, 100));
        $page = (int) $request->input('page', 1);
        $page = max(1, $page);
        $search = trim((string) $request->input('q', ''));

        $totalRevenue = (float) DB::table('orders')->sum('total_price');
        $totalOrders = (int) DB::table('orders')->count();
        $totalCustomers = (int) DB::table('orders')->whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $avgOrder = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 0) : 0;
        $todayRevenue = (float) DB::table('orders')->whereDate('order_date', now()->toDateString())->sum('total_price');

        $query = DB::table('orders as o')
            ->leftJoin('users as u', 'u.user_id', '=', 'o.user_id')
            ->leftJoin('order_items as oi', 'oi.order_id', '=', 'o.order_id')
            ->selectRaw('o.order_id as id, o.order_date, o.total_price, u.name as user_name, u.email as user_email, COUNT(oi.order_item_id) as items')
            ->groupBy('o.order_id', 'o.order_date', 'o.total_price', 'u.name', 'u.email')
            ->orderByDesc('o.order_date');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('o.order_id', 'like', "%{$search}%")
                  ->orWhere('u.name', 'like', "%{$search}%")
                  ->orWhere('u.email', 'like', "%{$search}%");
            });
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
            'stats' => [
                'totalRevenue'  => $totalRevenue,
                'totalOrders'   => $totalOrders,
                'totalCustomers'=> $totalCustomers,
                'avgOrder'      => $avgOrder,
                'todayRevenue'  => $todayRevenue,
            ],
        ]);
    }
}
