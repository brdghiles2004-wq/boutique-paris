<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Homme
            ['name' => 'T-shirt Essentiel', 'category' => 'homme-t-shirts', 'price' => 1200],
            ['name' => 'Pull Cachemire', 'category' => 'homme-pulls', 'price' => 3500, 'sale_price' => 2800],
            ['name' => 'Veste Légère', 'category' => 'homme-vestes', 'price' => 4200],
            ['name' => 'Jean Slim', 'category' => 'homme-jeans', 'price' => 2800],
            ['name' => 'Casquette Classic', 'category' => 'homme-casquettes', 'price' => 800],
            ['name' => 'Sweat à Capuche', 'category' => 'homme-sweats-hoodies', 'price' => 2200],

            // Femme
            ['name' => 'Robe Élégante', 'category' => 'femme-robes', 'price' => 3800, 'sale_price' => 2990],
            ['name' => 'Pull Oversize', 'category' => 'femme-pulls', 'price' => 2600],
            ['name' => 'Veste Tendance', 'category' => 'femme-vestes', 'price' => 4500],
            ['name' => 'Jean Taille Haute', 'category' => 'femme-jeans', 'price' => 3000],
            ['name' => 'Sac à Main', 'category' => 'femme-sacs-main', 'price' => 5200],
            ['name' => 'Legging Sport', 'category' => 'femme-leggings', 'price' => 1500],

            // Bébé
            ['name' => 'Body Coton Doux', 'category' => 'bebe-bodies', 'price' => 900],
            ['name' => 'Pyjama Étoiles', 'category' => 'bebe-pyjamas', 'price' => 1200],
            ['name' => 'Bonnet Laine', 'category' => 'bebe-bonnets', 'price' => 500],
            ['name' => 'Ensemble Naissance', 'category' => 'bebe-ensembles-bebe', 'price' => 2200],

            // Enfants
            ['name' => 'T-shirt Fun', 'category' => 'enfants-t-shirts', 'price' => 900],
            ['name' => 'Jean Confort', 'category' => 'enfants-jeans', 'price' => 1800],
            ['name' => 'Veste Sport', 'category' => 'enfants-vestes', 'price' => 2400],
            ['name' => 'Baskets Cool', 'category' => 'enfants-baskets', 'price' => 2800],
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        $colors = [
            ['name' => 'Noir', 'hex' => '#1a1a1a'],
            ['name' => 'Blanc', 'hex' => '#f5f5f5'],
            ['name' => 'Bleu', 'hex' => '#2563eb'],
            ['name' => 'Gris', 'hex' => '#6b7280'],
        ];

        foreach ($products as $i => $data) {
            $category = Category::where('slug', $data['category'])->first();
            if (! $category) continue;

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . ($i + 1),
                'description' => "Découvrez notre {$data['name']} — qualité premium, style intemporel.",
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'is_active' => true,
                'is_featured' => $i % 3 === 0, // كل 3 منتجات وحدة مميزة
            ]);

            // نزيدو variants (مقاسات + ألوان)
            foreach ($sizes as $size) {
                $color = $colors[array_rand($colors)];
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => $color['name'],
                    'color_hex' => $color['hex'],
                    'sku' => strtoupper(Str::random(8)),
                    'stock' => rand(5, 30),
                    'extra_price' => 0,
                ]);
            }
        }
    }
}