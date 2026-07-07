<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cashflow_categories', function (Blueprint $table) {
            $table->unsignedInteger('position')->default(0)->after('icon');
        });

        // Backfill: per user, keep current order (by id)
        $categories = DB::table('cashflow_categories')
            ->orderBy('id')
            ->get()
            ->groupBy('user_id');

        foreach ($categories as $userCategories) {
            foreach (array_values($userCategories->all()) as $index => $category) {
                DB::table('cashflow_categories')
                    ->where('id', $category->id)
                    ->update(['position' => $index]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('cashflow_categories', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
