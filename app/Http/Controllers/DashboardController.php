<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        return $this->dashboardData(request());
    }

    public function petugas(Request $request): View
    {
        return $this->dashboardData($request);
    }

    private function dashboardData(Request $request): View
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Scope Warung
        |--------------------------------------------------------------------------
        */

        if ($user->isAdmin()) {
            $warungIds = Warung::where('status', true)
                ->pluck('id');
        } else {
            $warungIds = $user->assignedWarungs()
                ->where('warungs.status', true)
                ->pluck('warungs.id');
        }

        /*
        |--------------------------------------------------------------------------
        | Query Order
        |--------------------------------------------------------------------------
        */

        $ordersQuery = Order::whereIn('warung_id', $warungIds);

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalWarungs = $warungIds->count();

        $totalProducts = Product::where('status', true)->count();

        $totalSubmitted = (clone $ordersQuery)
            ->where('status', OrderStatus::SUBMITTED->value)
            ->count();

        $totalProcessing = (clone $ordersQuery)
            ->where('status', OrderStatus::PROCESSING->value)
            ->count();

        $totalReady = (clone $ordersQuery)
            ->where('status', OrderStatus::READY->value)
            ->count();

        $totalCompleted = (clone $ordersQuery)
            ->where('status', OrderStatus::COMPLETED->value)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Nilai Kebutuhan
        |--------------------------------------------------------------------------
        |
        | Tidak menghitung order yang dibatalkan.
        |
        */

        $totalPrice = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.warung_id', $warungIds)
            ->where('orders.status', '!=', OrderStatus::CANCELLED->value)
            ->sum(
                DB::raw('order_items.quantity * products.price')
            );

        /*
        |--------------------------------------------------------------------------
        | Kebutuhan Terbaru
        |--------------------------------------------------------------------------
        */

        $latestOrders = (clone $ordersQuery)
            ->with([
                'warung',
                'createdBy',
                'items',
            ])
            ->latest('order_date')
            ->latest('id')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Produk Paling Banyak Dibutuhkan
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.warung_id', $warungIds)
            ->where('orders.status', '!=', OrderStatus::CANCELLED->value)
            ->select(
                'products.id',
                'products.name',
                'products.code',
                'products.unit',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->groupBy(
                'products.id',
                'products.name',
                'products.code',
                'products.unit'
            )
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalWarungs',
            'totalProducts',
            'totalSubmitted',
            'totalProcessing',
            'totalReady',
            'totalCompleted',
            'totalPrice',
            'latestOrders',
            'topProducts'
        ));
    }
}
