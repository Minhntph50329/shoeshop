<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        // 1. Check if the user is client
        if (auth()->user()->role !== 'client') {
            return back()->with('error', 'Chỉ tài khoản khách hàng mới được đánh giá sản phẩm.');
        }

        $request->validate([
            'order_id'       => 'required|integer',
            'order_item_id'  => 'required|integer',
            'rating'         => 'required|integer|min:1|max:5',
            'review_text'    => 'required|string|min:5|max:1000',
            'images'         => 'nullable|array|max:5',
            'images.*'       => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'rating.required'      => 'Vui lòng chọn số sao đánh giá.',
            'review_text.required'  => 'Vui lòng nhập nội dung đánh giá.',
            'review_text.min'       => 'Nội dung đánh giá phải có ít nhất 5 ký tự.',
            'images.max'            => 'Bạn chỉ được tải lên tối đa 5 hình ảnh.',
            'images.*.image'        => 'Tệp tải lên phải là hình ảnh.',
            'images.*.max'          => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($request->order_id);
        
        // 2. Check if the order status is "Nhận hàng thành công" (id=10)
        $currentStatus = $order->getCurrentStatus();
        if (!$currentStatus || $currentStatus->id !== 10) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đã nhận hàng thành công.');
        }

        // 3. Check if the order item belongs to this order
        $orderItem = OrderItem::where('order_id', $order->id)->findOrFail($request->order_item_id);

        // 4. Check if this order item has already been reviewed
        $existingReview = Review::where('order_item_id', $orderItem->id)->first();
        if ($existingReview) {
            return back()->with('error', 'Sản phẩm này trong đơn hàng đã được đánh giá trước đó.');
        }

        DB::transaction(function () use ($request, $orderItem, $order) {
            // Create review
            $review = Review::create([
                'product_id'    => $orderItem->product_id,
                'order_id'      => $order->id,
                'order_item_id' => $orderItem->id,
                'user_id'       => auth()->id(),
                'rating'        => $request->rating,
                'review_text'   => $request->review_text,
                'is_active'     => true,
                'has_replies'   => false,
            ]);

            // Handle images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('reviews', 'public');
                    ReviewImage::create([
                        'review_id'  => $review->id,
                        'image_path' => 'storage/' . $path,
                    ]);
                }
            }
        });

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá sản phẩm!');
    }
}
