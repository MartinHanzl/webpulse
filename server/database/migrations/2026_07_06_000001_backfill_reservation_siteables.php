<?php

use App\Models\Restaurant\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill siteables rows for restaurant reservations from their table's site.
     */
    public function up(): void
    {
        $reservations = DB::table('reservations')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('siteables')
                    ->whereColumn('siteables.siteable_id', 'reservations.id')
                    ->where('siteables.siteable_type', Reservation::class);
            })
            ->get(['id', 'table_id']);

        foreach ($reservations as $reservation) {
            $siteIds = DB::table('siteables')
                ->where('siteable_type', \App\Models\Restaurant\RestaurantTable::class)
                ->where('siteable_id', $reservation->table_id)
                ->pluck('site_id');

            foreach ($siteIds as $siteId) {
                DB::table('siteables')->insert([
                    'site_id' => $siteId,
                    'siteable_type' => Reservation::class,
                    'siteable_id' => $reservation->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('siteables')
            ->where('siteable_type', Reservation::class)
            ->delete();
    }
};
