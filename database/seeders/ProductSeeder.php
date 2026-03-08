<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Sambal Tuna ',
                'variant' => 'Tuna',
                'description' => 'Sambal dengan ikan tuna pilihan, pedas mantap cocok untuk semua masakan',
                'price' => 35000,
                'stock' => 50,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Sambal Cumi ',
                'variant' => 'Cumi',
                'description' => 'Sambal dengan cumi asin pilihan, aroma khas seafood yang menggoda',
                'price' => 42000,
                'stock' => 40,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Sambal Bawang ',
                'variant' => 'Bawang',
                'description' => 'Sambal bawang dengan campuran bawang goreng renyah, aroma harum dan gurih',
                'price' => 28000,
                'stock' => 60,
                'is_active' => true,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
    }
}