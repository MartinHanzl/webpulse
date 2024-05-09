<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Administrator\Administrator;
use App\Models\Administrator\AdministratorGroup;
use App\Models\Language;
use App\Services\GoogleTranslatorService;
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
            'password' => Hash::make('MarHan537'),
            'verification_code' => Str::random(64),
            'invitation_code' => Str::random(6),
            'active' => true,
            'verified' => true,
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::factory(10)->create();

        $this->seedLanguages();
        $this->seedAdministratorsGroup();
        $this->seedAdministrators();
    }

    private function seedLanguages(): void
    {
        $languages = [
            ['name' => 'Čeština', 'code' => 'cs', 'iso' => 'cs-CZ', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Angličtina', 'code' => 'en', 'iso' => 'en-GB', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Němčina', 'code' => 'de', 'iso' => 'de-DE', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Polština', 'code' => 'pl', 'iso' => 'pl-PL', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Slovenština', 'code' => 'sk', 'iso' => 'sk-SK', 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ];
        foreach ($languages as $language) {
            DB::table('languages')->insert($language);
        }

        $languages = Language::all();

        $gTranslateClient = new GoogleTranslatorService();
        foreach ($languages as $language) {
            $langs = Language::all();
            foreach ($langs as $lang) {
                $language->translations()->create([
                    'name' => $gTranslateClient->translate($language->name, 'cs', $lang->code),
                    'locale' => $lang->code
                ]);
            }
        }
    }

    private function seedAdministratorsGroup(): void
    {
        $permissions = [
            'dashboard' => true,
            'stats' => true,
            'services' => true,
            'events' => true,
            'blog' => true,
            'pages' => true,
            'carrer' => true,
            'references' => true,
            'faq' => true,
            'users' => true,
            'user_questions' => true,
            'emails' => true,
            'newsletters' => true,
            'employees' => true,
            'invoices' => true,
            'administrators' => true,
            'permissions' => true,
            'settings' => true,
            'languages' => true,
            'socials' => true,
            'links' => true,
            'seo' => true,
        ];
        DB::table('administrator_groups')->insert([
            'name' => 'Administrátor',
            'permissions' => json_encode($permissions),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    private function seedAdministrators(): void
    {
        DB::table('administrators')->insert([
            'firstname' => 'Martin',
            'lastname' => 'Hanzl',
            'phone_prefix' => '+420',
            'phone' => '773284824',
            'email' => 'martas.hanzl@email.cz',
            'password' => Hash::make('MarHan537'),
            'active' => true,
            'group_id' => 1,
        ]);
    }
}
