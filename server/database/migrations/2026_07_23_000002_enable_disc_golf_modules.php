<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $slugs = ['courses', 'players', 'games'];

    // Disc golf is a personal module — enable it only on site #1.
    private int $siteId = 1;

    public function up(): void
    {
        $site = DB::table('sites')->where('id', $this->siteId)->first(['id', 'settings']);
        if (! $site) {
            return;
        }

        $settings = json_decode($site->settings ?? '{}', true) ?: [];
        $enabled = $settings['enabled_modules'] ?? [];

        $settings['enabled_modules'] = array_values(array_unique(array_merge($enabled, $this->slugs)));

        DB::table('sites')->where('id', $site->id)->update([
            'settings' => json_encode($settings),
        ]);
    }

    public function down(): void
    {
        $site = DB::table('sites')->where('id', $this->siteId)->first(['id', 'settings']);
        if (! $site) {
            return;
        }

        $settings = json_decode($site->settings ?? '{}', true) ?: [];
        $enabled = $settings['enabled_modules'] ?? [];

        $settings['enabled_modules'] = array_values(array_diff($enabled, $this->slugs));

        DB::table('sites')->where('id', $site->id)->update([
            'settings' => json_encode($settings),
        ]);
    }
};
