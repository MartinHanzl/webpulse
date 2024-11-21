<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        $this->seedUsers();
    }

    private function seedUsers(): void
    {
        $users = [
            [
                'firstname' => 'Martin',
                'lastname' => 'Hanzl',
                'email' => 'martas.hanzl@email.cz',
                'password' => Hash::make('pstruh01'),
                'phone' => '773284824',
                'street' => 'Blato 102',
                'city' => 'Mikulovice',
                'zip' => 53002,
                'invitation_token' => 'LYLPSXV6',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
