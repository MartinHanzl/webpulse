<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $slug = 'calorie_measurements';

    // Enable the calorie calculator on every site that already uses foodstuffs.
    public function up(): void
    {
        foreach (DB::table('sites')->get(['id', 'settings']) as $site) {
            $settings = json_decode($site->settings ?? '{}', true) ?: [];
            $enabled = $settings['enabled_modules'] ?? [];

            if (! in_array('foodstuffs', $enabled, true)) {
                continue;
            }

            $settings['enabled_modules'] = array_values(array_unique(array_merge($enabled, [$this->slug])));

            DB::table('sites')->where('id', $site->id)->update([
                'settings' => json_encode($settings),
            ]);
        }
    }

    public function down(): void
    {
        foreach (DB::table('sites')->get(['id', 'settings']) as $site) {
            $settings = json_decode($site->settings ?? '{}', true) ?: [];
            $enabled = $settings['enabled_modules'] ?? [];

            $settings['enabled_modules'] = array_values(array_diff($enabled, [$this->slug]));

            DB::table('sites')->where('id', $site->id)->update([
                'settings' => json_encode($settings),
            ]);
        }
    }
};
