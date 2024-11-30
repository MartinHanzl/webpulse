<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User\UserGroup;
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
        $this->seedUserGroups();
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
                'user_group_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }

    private function seedUserGroups(): void
    {
        $userGroups = [
            [
                'name' => 'Admin',
                'permissions' => [
                    [

                        'name' => 'Uživatelé',
                        'slug' => 'users', // slug based on table name
                        'permissions' => [
                            'view' => true,
                            'edit' => true,
                            'delete' => true
                        ]
                    ],
                    [

                        'name' => 'Uživatelské skupiny',
                        'slug' => 'user_group',
                        'permissions' => [
                            'view' => true,
                            'edit' => true,
                            'delete' => true
                        ]
                    ]
                ]
            ]
        ];

        foreach ($userGroups as $userGroup) {
            $item = new UserGroup();
            $item->fill([
                'name' => $userGroup['name'],
                'permissions' => json_encode($userGroup['permissions'])
            ]);
            $item->save();
        }
    }
}
