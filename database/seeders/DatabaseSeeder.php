<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        // Hot Pot Buffet Categories and Products
        $categories = [
            [
                'name' => '🥩 Premium Meats',
                'sort_order' => 1,
                'products' => [
                    ['name' => 'Wagyu Beef Slices', 'description' => 'Premium marbled wagyu beef, thinly sliced', 'price' => 18.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Angus Beef', 'description' => 'High-quality angus beef slices', 'price' => 12.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Lamb Shoulder', 'description' => 'Tender lamb shoulder slices', 'price' => 14.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Pork Belly', 'description' => 'Fatty and flavorful pork belly slices', 'price' => 10.99, 'is_spicy' => false, 'is_vegetarian' => false],
                ]
            ],
            [
                'name' => '🦐 Fresh Seafood',
                'sort_order' => 2,
                'products' => [
                    ['name' => 'Tiger Prawns', 'description' => 'Large fresh tiger prawns', 'price' => 16.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Scallops', 'description' => 'Sweet and tender scallops', 'price' => 15.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Fish Fillet', 'description' => 'Fresh white fish fillet slices', 'price' => 11.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Squid Rings', 'description' => 'Fresh squid cut into rings', 'price' => 9.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Mussels', 'description' => 'Fresh green mussels', 'price' => 8.99, 'is_spicy' => false, 'is_vegetarian' => false],
                ]
            ],
            [
                'name' => '🥬 Fresh Vegetables',
                'sort_order' => 3,
                'products' => [
                    ['name' => 'Bok Choy', 'description' => 'Fresh Chinese cabbage', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Napa Cabbage', 'description' => 'Sweet and crunchy napa cabbage', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Spinach', 'description' => 'Fresh baby spinach leaves', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Mushroom Platter', 'description' => 'Assorted fresh mushrooms (shiitake, enoki, oyster)', 'price' => 7.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Lotus Root', 'description' => 'Crispy lotus root slices', 'price' => 5.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Winter Melon', 'description' => 'Fresh winter melon slices', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                ]
            ],
            [
                'name' => '🍜 Noodles & Dumplings',
                'sort_order' => 4,
                'products' => [
                    ['name' => 'Udon Noodles', 'description' => 'Thick Japanese wheat noodles', 'price' => 3.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Glass Noodles', 'description' => 'Transparent vermicelli noodles', 'price' => 3.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Ramen Noodles', 'description' => 'Fresh ramen noodles', 'price' => 3.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Pork Dumplings', 'description' => 'Handmade pork and chive dumplings (8 pcs)', 'price' => 6.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Vegetable Dumplings', 'description' => 'Mixed vegetable dumplings (8 pcs)', 'price' => 5.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Shrimp Wontons', 'description' => 'Delicate shrimp wontons (8 pcs)', 'price' => 7.99, 'is_spicy' => false, 'is_vegetarian' => false],
                ]
            ],
            [
                'name' => '🍲 Tofu & Balls',
                'sort_order' => 5,
                'products' => [
                    ['name' => 'Soft Tofu', 'description' => 'Silky soft tofu', 'price' => 3.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Fried Tofu Puffs', 'description' => 'Crispy fried tofu puffs', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Fish Balls', 'description' => 'Bouncy fish balls', 'price' => 5.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Beef Balls', 'description' => 'Handmade beef balls', 'price' => 6.99, 'is_spicy' => false, 'is_vegetarian' => false],
                    ['name' => 'Crab Sticks', 'description' => 'Imitation crab sticks', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => false],
                ]
            ],
            [
                'name' => '🌶️ Spicy Options',
                'sort_order' => 6,
                'products' => [
                    ['name' => 'Spicy Beef', 'description' => 'Pre-marinated spicy beef slices', 'price' => 13.99, 'is_spicy' => true, 'is_vegetarian' => false],
                    ['name' => 'Spicy Pork', 'description' => 'Korean-style spicy pork', 'price' => 11.99, 'is_spicy' => true, 'is_vegetarian' => false],
                    ['name' => 'Kimchi', 'description' => 'Fermented spicy cabbage', 'price' => 4.99, 'is_spicy' => true, 'is_vegetarian' => true],
                    ['name' => 'Spicy Fish Cake', 'description' => 'Korean spicy fish cake slices', 'price' => 6.99, 'is_spicy' => true, 'is_vegetarian' => false],
                ]
            ],
            [
                'name' => '🥤 Beverages',
                'sort_order' => 7,
                'products' => [
                    ['name' => 'Soft Drinks', 'description' => 'Coke, Sprite, Fanta', 'price' => 2.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Fresh Coconut Water', 'description' => 'Natural coconut water', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Thai Iced Tea', 'description' => 'Sweet and creamy Thai tea', 'price' => 3.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Lychee Juice', 'description' => 'Sweet lychee juice', 'price' => 3.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Green Tea (Hot)', 'description' => 'Traditional hot green tea', 'price' => 2.99, 'is_spicy' => false, 'is_vegetarian' => true],
                ]
            ],
            [
                'name' => '🍨 Desserts',
                'sort_order' => 8,
                'products' => [
                    ['name' => 'Mochi Ice Cream', 'description' => 'Japanese rice cake with ice cream (3 pcs)', 'price' => 5.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Fried Ice Cream', 'description' => 'Crispy fried vanilla ice cream', 'price' => 6.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Fresh Fruit Platter', 'description' => 'Seasonal fresh fruits', 'price' => 7.99, 'is_spicy' => false, 'is_vegetarian' => true],
                    ['name' => 'Sweet Red Bean Soup', 'description' => 'Traditional Asian dessert soup', 'price' => 4.99, 'is_spicy' => false, 'is_vegetarian' => true],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $products = $categoryData['products'];
            unset($categoryData['products']);
            
            $category = \App\Models\Category::create($categoryData);
            
            foreach ($products as $productData) {
                $productData['category_id'] = $category->id;
                $productData['is_available'] = true;
                \App\Models\Product::create($productData);
            }
        }
    }
}
