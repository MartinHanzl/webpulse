<?php

namespace App\Console\Commands\Manual;

use App\Models\Food\Allergen\Allergen;
use App\Models\Food\Foodstuff\Foodstuff;
use App\Models\Food\Foodstuff\FoodstuffCategory;
use App\Models\Site\Site;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportRohlikFoodstuffs extends Command
{
    protected $signature = 'rohlik:import-foodstuffs
        {--site= : ID webu, ke kterému se maj\xC3\xAD nalezen\xC3\xA9 potraviny a kategorie p\xC5\x99ipojit (Siteable)}
        {--delay=1500 : Prodleva mezi po\xC5\xBEadavky na rohlik.cz v milisekund\xC3\xA1ch}
        {--limit= : Maxim\xC3\xA1ln\xC3\xAD po\xC4\x8Det produkt\xC5\xAF ke zpracov\xC3\xA1n\xC3\xAD v tomto b\xC4\x9bhu}';

    protected $description = 'Sta\xC3\xA1hne cel\xC3\xBD sitemap_products.xml z rohlik.cz a naimportuje produkty do foodstuffs + foodstuff_categories (resumable p\xC5\x99es rohlik_id).';

    private const SITEMAP_URL = 'https://www.rohlik.cz/sitemap_products.xml';

    private const USER_AGENT = 'ClaudeBot';

    private const LOCALE = 'cs';

    public function handle(): int
    {
        $siteId = (int) $this->option('site');
        if (! $siteId || ! Site::find($siteId)) {
            $this->error('Zadej platn\xC3\xA9 --site=<id> (existuj\xC3\xADc\xC3\xAD web).');

            return self::FAILURE;
        }

        $delayMs = max(0, (int) $this->option('delay'));
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $this->info('Stahuji sitemap_products.xml...');
        $urls = $this->fetchSitemapUrls();

        if (empty($urls)) {
            $this->error('Sitemap se nepoda\xC5\x99ilo na\xC4\x8D\xC3\xADst nebo je pr\xC3\xA1zdn\xC3\xBD.');

            return self::FAILURE;
        }

        $this->info(count($urls)." produkt\xC5\xAF v sitemapu.");

        $imported = 0;
        $skipped = 0;
        $failed = 0;
        $processed = 0;

        $this->output->progressStart(count($urls));

        foreach ($urls as $url) {
            if ($limit && $processed >= $limit) {
                break;
            }

            $processed++;
            $this->output->progressAdvance();

            $rohlikId = $this->extractRohlikId($url);
            if (! $rohlikId) {
                $failed++;

                continue;
            }

            if (Foodstuff::where('rohlik_id', $rohlikId)->exists()) {
                $skipped++;

                continue;
            }

            if ($delayMs > 0) {
                usleep($delayMs * 1000);
            }

            try {
                $response = Http::withHeaders([
                    'User-Agent' => self::USER_AGENT,
                    'Accept-Language' => 'cs-CZ',
                ])->timeout(15)->retry(2, 500)->get($url);

                if (! $response->successful()) {
                    Log::warning('Rohlik import: HTTP '.$response->status(), ['url' => $url]);
                    $failed++;

                    continue;
                }

                $product = $this->parseProduct($response->body(), $rohlikId);
            } catch (\Throwable $e) {
                Log::warning('Rohlik import: fetch failed', ['url' => $url, 'message' => $e->getMessage()]);
                $failed++;

                continue;
            }

            if (! $product) {
                $failed++;

                continue;
            }

            try {
                DB::transaction(function () use ($product, $siteId) {
                    $category = $this->resolveCategoryTree($product['breadcrumb'], $siteId);

                    $foodstuff = new Foodstuff;
                    $foodstuff->fill([
                        'macronutrients' => $product['macronutrients'],
                        'rohlik_id' => $product['rohlikId'],
                    ]);
                    $foodstuff->save();

                    $foodstuff->translateOrNew(self::LOCALE)->fill([
                        'name' => $product['name'],
                        'slug' => $product['slug'],
                        'perex' => $product['perex'],
                        'text' => $product['text'],
                    ]);
                    $foodstuff->save();

                    if ($category) {
                        $foodstuff->categories()->syncWithoutDetaching([$category->id]);
                    }

                    $allergenIds = $this->resolveAllergenIds($product['allergenNames']);
                    if (! empty($allergenIds)) {
                        $foodstuff->allergens()->syncWithoutDetaching($allergenIds);
                    }

                    $foodstuff->sites()->syncWithoutDetaching([$siteId]);
                });

                $imported++;
            } catch (\Throwable $e) {
                Log::warning('Rohlik import: save failed', ['url' => $url, 'message' => $e->getMessage()]);
                $failed++;
            }
        }

        $this->output->progressFinish();

        $this->info("Hotovo. Naimportov\xC3\xA1no: {$imported}, p\xC5\x99esko\xC4\x8Deno (ji\xC5\xBE existuje): {$skipped}, chyba: {$failed}.");

        return self::SUCCESS;
    }

    private function fetchSitemapUrls(): array
    {
        $response = Http::withHeaders(['User-Agent' => self::USER_AGENT])
            ->timeout(30)
            ->get(self::SITEMAP_URL);

        if (! $response->successful()) {
            return [];
        }

        preg_match_all('/<loc>(.*?)<\/loc>/', $response->body(), $matches);

        return $matches[1] ?? [];
    }

    private function extractRohlikId(string $url): ?int
    {
        if (preg_match('#/(\d+)-[a-z0-9-]+/?(?:\?.*)?$#i', $url, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    private function parseProduct(string $html, int $rohlikId): ?array
    {
        $product = null;
        $breadcrumbList = null;

        if (preg_match_all('#<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>#s', $html, $ldMatches)) {
            foreach ($ldMatches[1] as $json) {
                $decoded = json_decode($json, true);
                if (! is_array($decoded)) {
                    continue;
                }
                if (($decoded['@type'] ?? null) === 'Product') {
                    $product = $decoded;
                } elseif (($decoded['@type'] ?? null) === 'BreadcrumbList') {
                    $breadcrumbList = $decoded;
                }
            }
        }

        if (! $product || empty($product['name'])) {
            return null;
        }

        $breadcrumb = [];
        foreach ($breadcrumbList['itemListElement'] ?? [] as $item) {
            if (! preg_match('#/c(\d+)-([a-z0-9-]+)#i', $item['item'] ?? '', $cm)) {
                continue;
            }
            $breadcrumb[] = [
                'rohlikId' => (int) $cm[1],
                'slug' => $cm[2],
                'name' => $item['name'] ?? $cm[2],
            ];
        }

        $composition = null;
        $descriptionHtml = null;

        if (preg_match('#<script id="__NEXT_DATA__"[^>]*>(.*?)</script>#s', $html, $ndMatch)) {
            $nextData = json_decode($ndMatch[1], true);
            $queries = $nextData['props']['pageProps']['dehydratedState']['queries'] ?? [];

            foreach ($queries as $query) {
                $key = $query['queryKey'] ?? null;
                if (is_array($key) && ($key[0] ?? null) === 'product' && ($key[1] ?? null) === 'detail-content') {
                    $composition = $query['state']['data']['composition'] ?? null;
                    $descriptionHtml = $query['state']['data']['description'] ?? null;
                    break;
                }
            }
        }

        $macronutrients = null;
        if (! empty($composition['nutritionalValues'][0]['values'])) {
            $values = $composition['nutritionalValues'][0]['values'];
            $macronutrients = [
                'calories' => $values['energyKCal']['amount'] ?? null,
                'proteins' => $values['protein']['amount'] ?? null,
                'carbohydrates' => $values['carbohydrates']['amount'] ?? null,
                'fats' => $values['fats']['amount'] ?? null,
                'fiber' => $values['fiber']['amount'] ?? null,
            ];
        }

        $descriptionHtml = $descriptionHtml ?: ('<p>'.e($product['description'] ?? '').'</p>');

        $perex = null;
        if (preg_match('#<p>(.*?)</p>#s', $descriptionHtml, $pm)) {
            $perex = trim(strip_tags($pm[1]));
        }

        return [
            'rohlikId' => $rohlikId,
            'name' => $product['name'],
            'slug' => Str::slug($product['name']),
            'perex' => $perex,
            'text' => $descriptionHtml,
            'macronutrients' => $macronutrients,
            'breadcrumb' => $breadcrumb,
            'allergenNames' => $composition['allergens']['contained'] ?? [],
        ];
    }

    private function resolveCategoryTree(array $breadcrumb, int $siteId): ?FoodstuffCategory
    {
        $parentId = null;
        $category = null;

        foreach ($breadcrumb as $level) {
            $category = FoodstuffCategory::where('rohlik_id', $level['rohlikId'])->first();

            if (! $category) {
                $category = new FoodstuffCategory;
                $category->fill([
                    'foodstuff_category_id' => $parentId,
                    'rohlik_id' => $level['rohlikId'],
                ]);
                $category->save();

                $category->translateOrNew(self::LOCALE)->fill([
                    'name' => $level['name'],
                    'slug' => Str::slug($level['slug']),
                ]);
                $category->save();
            }

            $category->sites()->syncWithoutDetaching([$siteId]);
            $parentId = $category->id;
        }

        return $category;
    }

    private function resolveAllergenIds(array $names): array
    {
        if (empty($names)) {
            return [];
        }

        $ids = [];
        foreach ($names as $name) {
            $allergen = Allergen::whereTranslation('name', $name)->first();
            if ($allergen) {
                $ids[] = $allergen->id;
            }
        }

        return $ids;
    }
}
