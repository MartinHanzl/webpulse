<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'firstname' => 'Martin',
            'lastname' => 'Hanzl',
            'phone_prefix' => '+420',
            'phone' => '773284824',
            'email' => 'martas.hanzl@email.cz',
            'street' => 'Blato 102',
            'postcode' => 53002,
            'city' => 'Mikulovice',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('pstruh01'),
            'verification_code' => Str::random(64),
            'invitation_code' => Str::random(6),
            'active' => true,
            'verified' => true,
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::factory(10)->create();

        $this->seedLanguages();
    }

    private function seedLanguages()
    {
        $languages = [
            ['name' => 'Čeština', 'locale' => 'cs', 'iso' => 'cs-CZ', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Angličtina', 'locale' => 'en', 'iso' => 'en-GB', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Němčina', 'locale' => 'de', 'iso' => 'de-DE', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Polština', 'locale' => 'pl', 'iso' => 'pl-PL', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Slovenština', 'locale' => 'sk', 'iso' => 'sk-SK', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ];
        foreach ($languages as $language) {
            DB::table('languages')->insert($language);
        }
    }
}
