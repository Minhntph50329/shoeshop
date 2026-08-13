<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'fullname' => 'Quản Trị Viên',
            'email' => 'admin@shoeshop.com',
            'phone_number' => '0988888888',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'gender' => 'male',
            'bank_name' => 'Vietcombank',
            'user_bank_name' => 'QUAN TRI VIEN',
            'bank_account' => '1234567890',
        ]);

        // Client user 1
        $client = User::create([
            'fullname' => 'Nguyễn Văn A',
            'email' => 'user@shoeshop.com',
            'phone_number' => '0912345678',
            'password' => Hash::make('password'),
            'role' => 'client',
            'status' => 'active',
            'gender' => 'male',
            'birthday' => '1995-05-20',
            'bank_name' => 'MB Bank',
            'user_bank_name' => 'NGUYEN VAN A',
            'bank_account' => '9876543210',
        ]);

        // Client user 2 (locked)
        User::create([
            'fullname' => 'Trần Thị B',
            'email' => 'locked@shoeshop.com',
            'phone_number' => '0933333333',
            'password' => Hash::make('password'),
            'role' => 'client',
            'status' => 'locked',
            'reason_lock' => 'Vi phạm chính sách thanh toán và спам đơn hàng.',
            'gender' => 'female',
        ]);

        // Address for Client 1
        UserAddress::create([
            'user_id' => $client->id,
            'fullname' => 'Nguyễn Văn A',
            'phone_number' => '0912345678',
            'email' => 'user@shoeshop.com',
            'address' => 'Số 123 Đường Nguyễn Trãi, Thanh Xuân, Hà Nội',
            'province' => 'Hà Nội',
            'district' => 'Thanh Xuân',
            'ward' => 'Thượng Đình',
            'street' => 'Nguyễn Trãi',
            'address_type' => 'home',
            'is_default' => true,
        ]);

        // Brand
        $brand = Brand::create([
            'name' => 'Nike',
            'slug' => 'nike',
            'is_active' => true,
        ]);

        // Category
        $category = Category::create([
            'name' => 'Giày Sneaker',
            'slug' => 'giay-sneaker',
            'is_active' => true,
        ]);

        // Product
        $product = Product::create([
            'name' => 'Nike Air Force 1',
            'slug' => 'nike-air-force-1',
            'brand_id' => $brand->id,
            'description' => 'Giày Nike Air Force 1 chính hãng thiết kế hiện đại, trẻ trung.',
            'price' => 2500000,
            'stock' => 50,
            'status' => 'active',
            'sku' => 'AF1-MAIN',
        ]);
        $product->categories()->attach($category->id);

        // Variant
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'AF1-WHITE-41',
            'price' => 2500000,
            'stock' => 20,
            'is_active' => true,
        ]);
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'AF1-WHITE-42',
            'price' => 2500000,
            'stock' => 15,
            'is_active' => true,
        ]);
    }
}
