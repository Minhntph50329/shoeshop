<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now   = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        // ── 1. Thẻ thống kê tổng quan ─────────────────────────────────
        // Doanh thu tháng này (đơn đã giao thành công – status 4 hoặc 10)
        $revenueThisMonth = Order::whereHas('statuses', function ($q) {
            $q->whereIn('order_status_id', [4, 10])
              ->where('order_order_status.is_current', true);
        })
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->sum(DB::raw('total_amount + shipping_fee - discount_amount'));

        // Tổng đơn hàng tháng này
        $ordersThisMonth = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)->count();

        // Tổng khách hàng
        $totalCustomers = User::where('role', 'client')->count();

        // Sản phẩm sắp hết hàng (stock <= 5)
        $lowStockCount = Product::where('stock', '<=', 5)->where('status', true)->count();

        // ── 2. Biểu đồ doanh thu 12 tháng gần nhất ───────────────────
        $revenueChart = [];
        $revenueLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt = $now->copy()->subMonths($i);
            $revenueLabels[] = 'T' . $dt->month . '/' . $dt->year;
            $revenueChart[]  = (float) Order::whereHas('statuses', function ($q) {
                $q->whereIn('order_status_id', [4, 10])
                  ->where('order_order_status.is_current', true);
            })
            ->whereMonth('created_at', $dt->month)
            ->whereYear('created_at', $dt->year)
            ->sum(DB::raw('total_amount + shipping_fee - discount_amount'));
        }

        // ── 3. Biểu đồ trạng thái đơn hàng ──────────────────────────
        $statusLabels = [
            1 => 'Chờ xác nhận',
            2 => 'Chờ lấy hàng',
            3 => 'Đang giao',
            4 => 'Đã giao',
            5 => 'Chờ trả hàng',
            6 => 'Đã trả hàng',
            7 => 'Hoàn tiền',
            8 => 'Đã hủy',
            9 => 'Gửi hàng',
            10 => 'Nhận thành công',
        ];

        $orderStatusCounts = DB::table('order_order_status')
            ->select('order_status_id', DB::raw('count(*) as total'))
            ->where('is_current', true)
            ->groupBy('order_status_id')
            ->pluck('total', 'order_status_id')
            ->toArray();

        $orderStatusChartLabels = [];
        $orderStatusChartData   = [];
        foreach ($statusLabels as $id => $label) {
            if (isset($orderStatusCounts[$id]) && $orderStatusCounts[$id] > 0) {
                $orderStatusChartLabels[] = $label;
                $orderStatusChartData[]   = $orderStatusCounts[$id];
            }
        }

        // ── 4. Top 10 sản phẩm bán chạy ─────────────────────────────
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with(['product' => function ($q) {
                $q->select('id', 'name', 'image', 'price', 'stock');
            }])
            ->whereHas('order.statuses', function ($q) {
                $q->whereIn('order_status_id', [4, 10])
                  ->where('order_order_status.is_current', true);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // ── 5. Đơn hàng mới nhất (10 đơn gần nhất) ──────────────────
        $latestOrders = Order::with(['statuses' => function ($q) {
            $q->where('order_order_status.is_current', true);
        }, 'user'])
        ->latest()
        ->limit(10)
        ->get();

        // ── 6. Sản phẩm sắp hết hàng (stock <= 10) ──────────────────
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('status', true)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // ── 7. Thông báo ─────────────────────────────────────────────
        $pendingOrders  = Order::whereHas('statuses', function ($q) {
            $q->where('order_status_id', 1)->where('order_order_status.is_current', true);
        })->count();

        $pendingRefunds = Refund::where('status', 'pending')->count();

        $newReviews = Review::where('is_active', true)
            ->whereDate('created_at', '>=', $now->copy()->subDays(7))
            ->count();

        $pendingContacts = Contact::where('status', 'pending')->count();

        return view('admin.dashboard.index', compact(
            'revenueThisMonth',
            'ordersThisMonth',
            'totalCustomers',
            'lowStockCount',
            'revenueChart',
            'revenueLabels',
            'orderStatusChartLabels',
            'orderStatusChartData',
            'topProducts',
            'latestOrders',
            'lowStockProducts',
            'pendingOrders',
            'pendingRefunds',
            'newReviews',
            'pendingContacts'
        ));
    }
}
