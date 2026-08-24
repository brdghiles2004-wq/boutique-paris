<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Homme' => [
                'T-shirts', 'Polos', 'Chemises', 'Pulls', 'Sweats & Hoodies',
                'Vestes', 'Manteaux', 'Jeans', 'Pantalons', 'Shorts',
                'Survêtements', 'Sous-vêtements', 'Pyjamas', 'Chaussettes',
                'Baskets', 'Chaussures classiques', 'Sandales',
                'Casquettes', 'Ceintures', 'Montres', 'Lunettes', 'Sacs', 'Portefeuilles', 'Accessoires',
            ],
            'Femme' => [
                'Robes', 'Jupes', 'T-shirts', 'Chemisiers', 'Pulls', 'Sweats & Hoodies',
                'Vestes', 'Manteaux', 'Jeans', 'Pantalons', 'Leggings', 'Shorts',
                'Ensembles', 'Lingerie', 'Pyjamas',
                'Sacs à main', 'Portefeuilles', 'Baskets', 'Talons', 'Chaussures plates', 'Bottes', 'Sandales',
                'Bijoux', 'Montres', 'Lunettes', 'Foulards', 'Accessoires',
            ],
            'Bébé' => [
                'Bodies', 'Ensembles bébé', 'Pyjamas', 'Vestes bébé',
                'Chaussures bébé', 'Bonnets', 'Bavoirs',
                'Couches', 'Sacs à langer', 'Jouets bébé', 'Accessoires bébé',
            ],
            'Enfants' => [
                'T-shirts', 'Chemises', 'Pulls', 'Vestes', 'Jeans',
                'Pantalons', 'Shorts', 'Survêtements',
                'Baskets', 'Sandales', 'Casquettes',
                'Robes', 'Jupes', 'Leggings', 'Ensembles', 'Sacs',
            ],
        ];

        foreach ($tree as $mainName => $subCategories) {
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($mainName)],
                ['name' => $mainName]
            );

            foreach ($subCategories as $order => $subName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($mainName . '-' . $subName)],
                    [
                        'name' => $subName,
                        'parent_id' => $parent->id,
                        'order' => $order,
                    ]
                );
            }
        }
    }
}