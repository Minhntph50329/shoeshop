<?php

namespace App\Services;

use App\Models\ShippingRate;
use Illuminate\Support\Str;

class ShippingService
{
    /**
     * @var string Tỉnh/Thành kho mặc định
     */
    protected string $defaultProvince = 'Hà Nội';

    /**
     * Xác định vùng miền của một tỉnh/thành
     */
    public function getRegionByProvince(string $province): ?string
    {
        $regions = config('provinces.regions');
        if (!$regions) {
            return null;
        }

        // Chuẩn hóa tên tỉnh để so sánh
        $normalizedProvince = $this->normalizeString($province);

        foreach ($regions as $region => $provinces) {
            foreach ($provinces as $p) {
                if ($this->normalizeString($p) === $normalizedProvince) {
                    return $region;
                }
            }
        }

        return null; // Không tìm thấy
    }

    /**
     * Tính phí giao hàng dựa trên tỉnh thành
     */
    public function calculateFee(string $province): float
    {
        $baseFee = (float) \App\Models\Setting::get('shipping_fee', 30000);
        $normalizedProvince = $this->normalizeString($province);
        $normalizedDefault = $this->normalizeString($this->defaultProvince);

        // 1. Nếu cùng tỉnh/thành phố với kho hàng
        if ($normalizedProvince === $normalizedDefault) {
            $defaultRegion = $this->getRegionByProvince($this->defaultProvince) ?? 'north';
            $rate = ShippingRate::where('region', $defaultRegion)
                                ->where('type', 'same_province')
                                ->first();
            $regionFee = $rate ? (float) $rate->fee : 30000;
            return $baseFee + $regionFee;
        }

        // 2. Tìm xem khách ở vùng nào
        $customerRegion = $this->getRegionByProvince($province);
        if (!$customerRegion) {
            // Fallback nếu không xác định được vùng (người dùng nhập sai chẳng hạn)
            return $baseFee;
        }

        $defaultRegion = $this->getRegionByProvince($this->defaultProvince) ?? 'north';

        // 3. Nếu cùng miền (nhưng khác tỉnh)
        if ($customerRegion === $defaultRegion) {
            $rate = ShippingRate::where('region', $customerRegion)
                                ->where('type', 'different_province')
                                ->first();
            $regionFee = $rate ? (float) $rate->fee : 40000;
            return $baseFee + $regionFee;
        }

        // 4. Nếu khác miền (different_region)
        $rate = ShippingRate::where('region', $customerRegion)
                            ->where('type', 'different_region')
                            ->first();
                            
        $regionFee = $rate ? (float) $rate->fee : 50000;
        return $baseFee + $regionFee;
    }

    /**
     * Chuẩn hóa chuỗi (Bỏ dấu, chuyển chữ thường) để so sánh dễ dàng hơn.
     */
    protected function normalizeString(string $str): string
    {
        // Có thể bổ sung bỏ chữ "Thành phố ", "Tỉnh " để an toàn hơn
        $str = str_ireplace(['thành phố ', 'tp ', 'tỉnh '], '', $str);
        
        $str = Str::slug($str, ' ');
        return trim(strtolower($str));
    }
}
