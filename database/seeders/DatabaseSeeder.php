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
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $cats = [
            ['name' => 'Popular', 'sort_order' => 1],
            ['name' => 'Burgers', 'sort_order' => 2],
            ['name' => 'Drinks', 'sort_order' => 3],
        ];

        foreach ($cats as $c) {
            $cat = \App\Models\Category::create($c);
            
            if ($cat->name === 'Burgers') {
                \App\Models\Product::create([
                    'category_id' => $cat->id,
                    'name' => 'Classic Cheeseburger',
                    'description' => 'Juicy beef patty with cheddar cheese, lettuce, tomato, and our secret sauce.',
                    'price' => 12.99,
                    'is_available' => true
                ]);
                \App\Models\Product::create([
                    'category_id' => $cat->id,
                    'name' => 'Bacon BBQ Burger',
                    'description' => 'Smoked bacon, onion rings, and BBQ sauce.',
                    'price' => 14.50,
                    'is_available' => true
                ]);
            }
             if ($cat->name === 'Drinks') {
                \App\Models\Product::create([
                    'category_id' => $cat->id,
                    'name' => 'Cola',
                    'description' => 'Ice cold cola.',
                    'price' => 2.50,
                    'is_available' => true
                ]);
                 \App\Models\Product::create([
                    'category_id' => $cat->id,
                    'name' => 'Lemonade',
                    'description' => 'Freshly squeezed lemonade.',
                    'price' => 3.50,
                    'is_available' => true
                ]);
            }
             if ($cat->name === 'Popular') {
                  \App\Models\Product::create([
                    'category_id' => $cat->id,
                    'name' => 'Fries',
                    'description' => 'Crispy golden fries.',
                    'price' => 4.99,
                    'is_available' => true
                ]);
             }
        }
    }
}
