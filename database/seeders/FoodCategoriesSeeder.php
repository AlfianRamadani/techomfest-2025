<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            "Sayur",
            "Buah",
            "Protein Hewani",
            "Protein Nabati",
            "Sumber Karbohidrat",
            "Olahan / Processed Food",
            "Gorengan",
            "Fast Food",
            "Snack / Camilan",
            "Dessert / Makanan Manis",
            "Minuman Manis",
            "Minuman Berkafein",
            "Dairy / Susu",
            "Fermentasi",
            "Tinggi Garam",
            "Tinggi Gula",
            "Tinggi Lemak"
        ];

        foreach ($categories as $name) {
            DB::table('food_categories')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
