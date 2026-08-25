<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['setting_key', 'setting_value', 'setting_type'];

    /**
     * Get a setting value by key.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set($key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value, 'setting_type' => $type]
        );
    }
}
