<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\NewsController;

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\WishlistController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ClientController::class, 'index'])->name('home');
Route::get('/shop', [ClientController::class, 'shop'])->name('shop');
Route::get('/products/{product}', [ClientController::class, 'productDetail'])->name('products.show');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove-multiple', [CartController::class, 'removeMultiple'])->name('cart.removeMultiple');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/address/create', [CheckoutController::class, 'createAddress'])->name('checkout.address.create');
Route::post('/checkout/address/create', [CheckoutController::class, 'storeAddress'])->name('checkout.address.store');
Route::get('/checkout/payment-online/{order_id}', [CheckoutController::class, 'paymentOnline'])->name('checkout.payment.online');
Route::post('/checkout/payment-online/{order_id}', [CheckoutController::class, 'processOnlinePayment'])->name('checkout.payment.online.submit');
Route::get('/checkout/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('checkout.vnpay.return');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');

// User Profile Routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
Route::delete('/profile/address/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');

// Client Order Routes
Route::get('/my-orders', [ClientOrderController::class, 'index'])->name('my-orders');
Route::get('/my-orders/{id}', [ClientOrderController::class, 'show'])->name('client.orders.show');
Route::post('/my-orders/{id}/cancel', [ClientOrderController::class, 'cancel'])->name('client.orders.cancel');
Route::post('/my-orders/{id}/confirm', [ClientOrderController::class, 'confirm'])->name('client.orders.confirm');
Route::post('/my-orders/{id}/update-address', [ClientOrderController::class, 'updateAddress'])->name('client.orders.updateAddress');
Route::get('/my-orders/{id}/refund', [\App\Http\Controllers\Client\RefundController::class, 'create'])->name('client.orders.refund');
Route::post('/my-orders/{id}/refund', [\App\Http\Controllers\Client\RefundController::class, 'store'])->name('client.orders.refund.store');
Route::post('/reviews', [\App\Http\Controllers\Client\ReviewController::class, 'store'])->name('reviews.store');

// Client Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{id}/comment', [BlogController::class, 'storeComment'])->name('blog.comment.store');

Route::get('/contact', [\App\Http\Controllers\Client\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Client\ContactController::class, 'store'])->name('contact.store');
Route::get('/my-contacts', [\App\Http\Controllers\Client\ContactController::class, 'myContacts'])->middleware('auth')->name('my-contacts');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Protected by 'admin' middleware)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products (Soft Deletes + Resource)
    Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
    Route::resource('products', ProductController::class);

    // Brands (Soft Deletes + Resource)
    Route::get('/brand/trash', [BrandController::class, 'trash'])->name('brand.trash');
    Route::post('/brand/{id}/restore', [BrandController::class, 'restore'])->name('brand.restore');
    Route::delete('/brand/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brand.forceDelete');
    Route::resource('brand', BrandController::class);

    // Categories (Soft Deletes + Resource)
    Route::get('/category/trash', [CategoryController::class, 'trash'])->name('category.trash');
    Route::post('/category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    Route::delete('/category/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');
    Route::resource('category', CategoryController::class);

    // Attributes (Soft Deletes + Values + Resource)
    Route::get('/attributes/trash', [AttributeController::class, 'trash'])->name('attributes.trash');
    Route::post('/attributes/{id}/restore', [AttributeController::class, 'restore'])->name('attributes.restore');
    Route::delete('/attributes/{id}/force-delete', [AttributeController::class, 'forceDelete'])->name('attributes.forceDelete');
    Route::post('/attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
    Route::delete('/attributes/values/{id}', [AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');
    Route::resource('attributes', AttributeController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Refund Management
    Route::get('/refunds', [\App\Http\Controllers\Admin\RefundController::class, 'index'])->name('refunds.index');
    Route::get('/refunds/{id}', [\App\Http\Controllers\Admin\RefundController::class, 'show'])->name('refunds.show');
    Route::post('/refunds/{id}/action', [\App\Http\Controllers\Admin\RefundController::class, 'handleAction'])->name('refunds.action');

    // Customers (Users Management: CRUD + Soft Delete + Trash + Restore + Force Delete)
    Route::get('/customers/trash', [CustomerController::class, 'trash'])->name('customers.trash');
    Route::post('/customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::delete('/customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.forceDelete');
    Route::put('/customers/{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle');
    Route::resource('customers', CustomerController::class);

    // Banner (CRUD + Soft Delete + Trash + Restore + Force Delete)
    Route::get('/banner/trash', [BannerController::class, 'trash'])->name('banner.trash');
    Route::post('/banner/{id}/restore', [BannerController::class, 'restore'])->name('banner.restore');
    Route::delete('/banner/{id}/force-delete', [BannerController::class, 'forceDelete'])->name('banner.forceDelete');
    Route::resource('banner', BannerController::class);

    // Voucher / Coupon (CRUD + Soft Delete + Trash + Restore + Force Delete)
    Route::get('/voucher/trash', [CouponController::class, 'trash'])->name('voucher.trash');
    Route::post('/voucher/{id}/restore', [CouponController::class, 'restore'])->name('voucher.restore');
    Route::delete('/voucher/{id}/force-delete', [CouponController::class, 'forceDelete'])->name('voucher.forceDelete');
    Route::resource('voucher', CouponController::class);

    // News / Blog Posts & Categories (CRUD + Soft Delete + Trash + Restore + Force Delete)
    Route::get('/news/trash', [NewsController::class, 'trash'])->name('news.trash');
    Route::post('/news/{id}/restore', [NewsController::class, 'restore'])->name('news.restore');
    Route::delete('/news/{id}/force-delete', [NewsController::class, 'forceDelete'])->name('news.forceDelete');
    Route::post('/news/category', [NewsController::class, 'storeCategory'])->name('news.category.store');
    Route::get('/news/categories', [NewsController::class, 'categories'])->name('news.categories');
    Route::put('/news/categories/{id}', [NewsController::class, 'updateCategory'])->name('news.categories.update');
    Route::delete('/news/categories/{id}', [NewsController::class, 'destroyCategory'])->name('news.categories.destroy');
    Route::resource('news', NewsController::class);

    // News Blog Comments Management
    Route::get('/news-comments', [NewsController::class, 'comments'])->name('news.comments');
    Route::post('/news-comments/{id}/reply', [NewsController::class, 'replyComment'])->name('news.comments.reply');
    Route::put('/news-comments/{id}/toggle', [NewsController::class, 'toggleCommentStatus'])->name('news.comments.toggle');
    Route::delete('/news-comments/{id}', [NewsController::class, 'destroyComment'])->name('news.comments.destroy');

    // Managers & Staffs & Reviews & Settings
    Route::view('/managers', 'admin.managers.index')->name('managers.index');
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/reply', [\App\Http\Controllers\Admin\ReviewController::class, 'reply'])->name('reviews.reply');
    Route::put('/reviews/{id}/toggle', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleActive'])->name('reviews.toggle');
    Route::delete('/reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::view('/settings', 'admin.settings.index')->name('settings.index');
    
    // Contact Management
    Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{id}/reply', [\App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('/contacts/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
});