<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Générer le sitemap XML';

    public function handle(): void
    {
        $urls = [];
        $baseUrl = config('app.url');

        // Pages statiques
        $staticPages = ['/', '/support', '/login', '/register'];
        foreach ($staticPages as $page) {
            $urls[] = [
                'loc'        => $baseUrl . $page,
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ];
        }

        // Catégories
        Category::where('is_active', true)->each(function ($cat) use ($baseUrl, &$urls) {
            $urls[] = [
                'loc'        => $baseUrl . '/categorie/' . $cat->slug,
                'changefreq' => 'weekly',
                'priority'   => '0.7',
                'lastmod'    => $cat->updated_at->toAtomString(),
            ];
        });

        // Produits
        Product::where('is_active', true)->each(function ($prod) use ($baseUrl, &$urls) {
            $urls[] = [
                'loc'        => $baseUrl . '/produit/' . $prod->slug,
                'changefreq' => 'weekly',
                'priority'   => '0.8',
                'lastmod'    => $prod->updated_at->toAtomString(),
            ];
        });

        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            }
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap généré: ' . public_path('sitemap.xml') . ' (' . count($urls) . ' URLs)');
    }
}