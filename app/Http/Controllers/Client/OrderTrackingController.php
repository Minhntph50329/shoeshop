<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $orderNumber = $request->input('order_number');
        $order = null;

        if ($orderNumber) {
            $order = Order::with(['items.product'])->where('orderNumber', $orderNumber)->first();
        }

        return Inertia::render('Client/OrderTrackingView', [
            'order' => $order
        ]);
    }
}
