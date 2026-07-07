<?php

namespace App\Console\Commands;

use App\Models\Site\Site;
use Astrotomic\Translatable\Translatable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateSitemaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemaps:generate {--site= : Generate sitemap only for the given site ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemaps for all active sites into storage/app/public/sitemaps';

    /**
     * Map of URL sections to siteable entities.
     * Section is the public URL prefix: https://{site}/{locale?}/{section}/{id}/{slug}
     * Filter limits the query to publicly visible records (mirrors Client controllers).
     *
     * @return array<string, array{model: class-string<Model>, filter?: callable(Builder): Builder}>
     */
    protected function entities(): array
    {
        return [
            'blog' => [
                'model' => \App\Models\Blog\Post::class,
                'filter' => fn (Builder $q) => $q->where('status', 'published')
                    ->where(function ($q) {
                        $q->whereNull('published_from')->orWhere('published_from', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('published_to')->orWhere('published_to', '>=', now());
                    }),
            ],
            'blog/category' => ['model' => \App\Models\Blog\PostCategory::class],
            'info' => [
                'model' => \App\Models\Page\Page::class,
                'filter' => fn (Builder $q) => $q->where('active', true),
            ],
            'news' => [
                'model' => \App\Models\Novelty\Novelty::class,
                'filter' => fn (Builder $q) => $q->where('active', true),
            ],
            'service' => ['model' => \App\Models\Service\Service::class],
            'event' => [
                'model' => \App\Models\Event\Event::class,
                'filter' => fn (Builder $q) => $q->where('status', 'published'),
            ],
            'event/category' => ['model' => \App\Models\Event\EventCategory::class],
            'review' => ['model' => \App\Models\Review\Review::class],
            'logo' => ['model' => \App\Models\Logo\Logo::class],
            'photo-gallery' => ['model' => \App\Models\PhotoGallery\PhotoGallery::class],
            'career' => [
                'model' => \App\Models\Career\Career::class,
                'filter' => fn (Builder $q) => $q->where('status', 'open'),
            ],
            'quiz' => [
                'model' => \App\Models\Quiz\Quiz::class,
                'filter' => fn (Builder $q) => $q->where('status', 'public'),
            ],
            'faq' => ['model' => \App\Models\Faq\Faq::class],
            'faq/category' => ['model' => \App\Models\Faq\FaqCategory::class],
            'allergen' => ['model' => \App\Models\Food\Allergen\Allergen::class],
            'food' => ['model' => \App\Models\Food\Foodstuff\Foodstuff::class],
            'food/category' => ['model' => \App\Models\Food\Foodstuff\FoodstuffCategory::class],
            'meal' => ['model' => \App\Models\Food\Meal\Meal::class],
            'meal/category' => ['model' => \App\Models\Food\Meal\MealCategory::class],
            'recipe' => ['model' => \App\Models\Food\Recipe\Recipe::class],
            'recipe/category' => ['model' => \App\Models\Food\Recipe\RecipeCategory::class],
            'menu' => ['model' => \App\Models\Food\Menu\Menu::class],
            'table' => ['model' => \App\Models\Restaurant\RestaurantTable::class],
            'apartment' => [
                'model' => \App\Models\Apartment\Apartment::class,
                'filter' => fn (Builder $q) => $q->where('status', '!=', 'draft'),
            ],
            'apartment/type' => ['model' => \App\Models\Apartment\ApartmentType::class],
            'building' => ['model' => \App\Models\Building\Building::class],
            'amenity' => ['model' => \App\Models\Amenity\Amenity::class],
            'season' => ['model' => \App\Models\Season\Season::class],
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sites = Site::where('is_active', true)
            ->when($this->option('site'), fn ($q, $id) => $q->where('id', $id))
            ->get();

        if ($sites->isEmpty()) {
            $this->warn('No active sites found.');

            return self::SUCCESS;
        }

        $languages = DB::table('languages')->pluck('code', 'id');

        foreach ($sites as $site) {
            try {
                $count = $this->generateForSite($site, $languages);
                $this->info("Sitemap for {$site->url} generated ({$count} URLs).");
            } catch (\Throwable $e) {
                $this->error("Sitemap for {$site->url} failed: {$e->getMessage()}");
                Log::error('Sitemap generation failed', [
                    'site_id' => $site->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return self::SUCCESS;
    }

    /**
     * Generate and store the sitemap for a single site. Returns the number of URLs.
     */
    protected function generateForSite(Site $site, $languages): int
    {
        $origin = ($site->is_secure ? 'https://' : 'http://').$site->url;
        [$defaultLocale, $locales] = $this->resolveLocales($site, $languages);

        $urls = [];

        // Homepage for every locale
        foreach ($locales as $locale) {
            $urls[] = [
                'loc' => $origin.$this->localePrefix($locale, $defaultLocale).'/',
                'lastmod' => now()->toAtomString(),
            ];
        }

        foreach ($this->entities() as $section => $entity) {
            $class = $entity['model'];

            if (! class_exists($class)) {
                continue;
            }

            $ids = DB::table('siteables')
                ->where('site_id', $site->id)
                ->where('siteable_type', $class)
                ->pluck('siteable_id');

            if ($ids->isEmpty()) {
                continue;
            }

            $query = $class::query()->whereIn('id', $ids);

            if (in_array(Translatable::class, class_uses_recursive($class))) {
                $query->with('translations');
            }

            if (isset($entity['filter'])) {
                $entity['filter']($query);
            }

            foreach ($query->get() as $model) {
                foreach ($locales as $locale) {
                    $slug = $this->resolveSlug($model, $locale, $defaultLocale);
                    $path = '/'.$section.'/'.$model->id.($slug ? '/'.$slug : '');

                    $urls[] = [
                        'loc' => $origin.$this->localePrefix($locale, $defaultLocale).$path,
                        'lastmod' => $model->updated_at?->toAtomString(),
                    ];
                }
            }
        }

        Storage::disk('public')->put('sitemaps/'.$site->url.'.xml', $this->renderXml($urls));

        return count($urls);
    }

    /**
     * Resolve site locales from settings. Entries mix language IDs and codes,
     * both are normalized to codes. Returns [defaultLocale, uniqueLocales].
     *
     * @return array{0: string, 1: array<int, string>}
     */
    protected function resolveLocales(Site $site, $languages): array
    {
        $toCode = fn ($value) => is_numeric($value) ? ($languages[(int) $value] ?? null) : $value;

        $default = $toCode($site->settings['default_locale'] ?? null) ?? 'cs';

        $locales = collect($site->settings['enabled_locales'] ?? [])
            ->map($toCode)
            ->filter()
            ->prepend($default)
            ->unique()
            ->values()
            ->all();

        return [$default, $locales];
    }

    /**
     * URL locale prefix following the Nuxt prefix_except_default strategy.
     */
    protected function localePrefix(string $locale, string $defaultLocale): string
    {
        return $locale === $defaultLocale ? '' : '/'.$locale;
    }

    /**
     * Resolve the URL slug for the given locale. Falls back to the default
     * locale translation, then to a slugified name/title, then to no slug.
     */
    protected function resolveSlug(Model $model, string $locale, string $defaultLocale): ?string
    {
        if (in_array(Translatable::class, class_uses_recursive($model))) {
            foreach (array_unique([$locale, $defaultLocale]) as $loc) {
                $translation = $model->translate($loc, false);

                if ($translation) {
                    if (! empty($translation->slug)) {
                        return $translation->slug;
                    }
                    if (! empty($translation->name)) {
                        return Str::slug($translation->name);
                    }
                    if (! empty($translation->title)) {
                        return Str::slug($translation->title);
                    }
                }
            }
        }

        // Non-translatable models: direct columns (e.g. quizzes.slug)
        $attributes = $model->getAttributes();

        if (! empty($attributes['slug'])) {
            return $attributes['slug'];
        }
        if (! empty($attributes['name'])) {
            return Str::slug($attributes['name']);
        }
        if (! empty($attributes['title'])) {
            return Str::slug($attributes['title']);
        }

        return null;
    }

    /**
     * Render the sitemap XML.
     *
     * @param array<int, array{loc: string, lastmod: ?string}> $urls
     */
    protected function renderXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= "\t<url>\n";
            $xml .= "\t\t<loc>".htmlspecialchars($url['loc'], ENT_XML1)."</loc>\n";
            if (! empty($url['lastmod'])) {
                $xml .= "\t\t<lastmod>".$url['lastmod']."</lastmod>\n";
            }
            $xml .= "\t</url>\n";
        }

        return $xml.'</urlset>'."\n";
    }
}
