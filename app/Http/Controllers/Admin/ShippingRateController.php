<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShippingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = ShippingRate::all()->groupBy('region');
        return view('admin.shipping_rates.index', compact('rates'));
    }

    /**
     * Store/Update all shipping rates + cấu hình chung từ Setting.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_fee'     => 'required|numeric|min:0',
            'free_shipping_min'=> 'required|numeric|min:0',
            'shipping_time'    => 'nullable|string|max:100',
            'rates'            => 'required|array',
            'rates.*.*'        => 'required|numeric|min:0',
        ]);

        // Lưu cấu hình chung vào Setting (giống Settings > Shipping)
        Setting::set('shipping_fee',           $request->shipping_fee, 'number');
        Setting::set('free_shipping_min',      $request->free_shipping_min, 'number');
        Setting::set('shipping_time',          $request->shipping_time ?? '2 - 5 ngày');
        Setting::set('allow_national_shipping', $request->has('allow_national_shipping') ? '1' : '0', 'boolean');

        // Lưu bảng phí theo vùng miền
        foreach ($request->rates as $region => $types) {
            foreach ($types as $type => $fee) {
                ShippingRate::updateOrCreate(
                    ['region' => $region, 'type' => $type],
                    ['fee'    => $fee]
                );
            }
        }

        Cache::forget('shipping_rates');

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Đã cập nhật phí vận chuyển thành công.');
    }
}

