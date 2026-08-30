<?php

use App\Models\Setting;

if (!function_exists('format_price')) {
    /**
     * Format giá tiền theo đơn vị tiền tệ từ Setting.
     * Hỗ trợ: VND/đ, USD/$, EUR/€, v.v.
     */
    function format_price($amount, $showSymbol = true)
    {
        $currency = Setting::get('currency', 'đ');
        $currency = strtoupper(trim($currency));

        // Map currency code to symbol and format
        $currencyMap = [
            'VND' => ['symbol' => 'đ', 'decimals' => 0, 'dec_point' => ',', 'thousands' => '.', 'position' => 'after'],
            'Đ'   => ['symbol' => 'đ', 'decimals' => 0, 'dec_point' => ',', 'thousands' => '.', 'position' => 'after'],
            'USD' => ['symbol' => '$', 'decimals' => 2, 'dec_point' => '.', 'thousands' => ',', 'position' => 'before'],
            '$'   => ['symbol' => '$', 'decimals' => 2, 'dec_point' => '.', 'thousands' => ',', 'position' => 'before'],
            'EUR' => ['symbol' => '€', 'decimals' => 2, 'dec_point' => ',', 'thousands' => '.', 'position' => 'after'],
            '€'   => ['symbol' => '€', 'decimals' => 2, 'dec_point' => ',', 'thousands' => '.', 'position' => 'after'],
            'GBP' => ['symbol' => '£', 'decimals' => 2, 'dec_point' => '.', 'thousands' => ',', 'position' => 'before'],
            '£'   => ['symbol' => '£', 'decimals' => 2, 'dec_point' => '.', 'thousands' => ',', 'position' => 'before'],
        ];

        // Default to VND format
        $config = $currencyMap[$currency] ?? ['symbol' => $currency, 'decimals' => 0, 'dec_point' => ',', 'thousands' => '.', 'position' => 'after'];

        $formatted = number_format($amount, $config['decimals'], $config['dec_point'], $config['thousands']);

        if (!$showSymbol) {
            return $formatted;
        }

        if ($config['position'] === 'before') {
            return $config['symbol'] . $formatted;
        }

        return $formatted . $config['symbol'];
    }
}
