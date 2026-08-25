<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminNewOrder;
use App\Mail\AdminReturnRequest;
use App\Mail\AdminNewReview;
use App\Mail\AdminLowStock;
use App\Mail\AdminNewCustomer;
use App\Mail\AdminNewContact;

class AdminNotificationService
{
    /**
     * Lấy email nhận thông báo của Admin
     */
    protected function getAdminEmail()
    {
        return Setting::get('notification_email', 'admin@veloce.vn');
    }

    /**
     * Gửi mail nếu chức năng thông báo được bật và không gặp lỗi
     */
    protected function sendIfEnabled($settingKey, $mailable)
    {
        if (Setting::get($settingKey, '1') == '1') {
            $email = $this->getAdminEmail();
            if (empty($email)) return;

            try {
                Mail::to($email)->send($mailable);
            } catch (\Exception $e) {
                Log::error("Failed to send admin notification for {$settingKey} to {$email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Đơn hàng mới
     */
    public function sendNewOrder($order)
    {
        $this->sendIfEnabled('notify_new_order', new AdminNewOrder($order));
    }

    /**
     * Yêu cầu trả hàng
     */
    public function sendReturnRequest($returnRequest)
    {
        $this->sendIfEnabled('notify_return', new AdminReturnRequest($returnRequest));
    }

    /**
     * Đánh giá mới
     */
    public function sendNewReview($review)
    {
        $this->sendIfEnabled('notify_review', new AdminNewReview($review));
    }

    /**
     * Cảnh báo sản phẩm sắp hết hàng
     */
    public function sendLowStock($product, $variant = null)
    {
        $this->sendIfEnabled('notify_low_stock', new AdminLowStock($product, $variant));
    }

    /**
     * Khách hàng mới đăng ký
     */
    public function sendNewCustomer($customer)
    {
        $this->sendIfEnabled('notify_new_customer', new AdminNewCustomer($customer));
    }

    /**
     * Liên hệ mới từ khách hàng
     */
    public function sendNewContact($contact)
    {
        $this->sendIfEnabled('notify_contact', new AdminNewContact($contact));
    }
}
