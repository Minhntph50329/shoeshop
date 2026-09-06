<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Kiểm tra chế độ bảo trì từ database Setting.
     * - Admin/Super Admin vẫn truy cập được bình thường.
     * - Khách & người dùng thường sẽ thấy trang bảo trì 503.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenanceOn = \App\Models\Setting::get('maintenance_mode', '0') == '1';

        if ($isMaintenanceOn) {
            // Admin vẫn được phép vào
            if (auth()->check() && auth()->user()->hasAnyRole(['Admin', 'Super Admin', 'Staff'])) {
                return $next($request);
            }

            $message = \App\Models\Setting::get(
                'maintenance_message',
                'Website đang được bảo trì. Vui lòng quay lại sau.'
            );

            return response()->view('maintenance', ['message' => $message], 503);
        }

        return $next($request);
    }
}
