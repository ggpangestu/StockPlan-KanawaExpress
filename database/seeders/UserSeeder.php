<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner',
                'username' => 'owner',
                'password' => Hash::make('123456'),
                'role' => 'owner',
            ],

            [
                'name' => 'Produksi',
                'username' => 'produksi',
                'password' => Hash::make('123456'),
                'role' => 'produksi',
            ],

            [
                'name' => 'Armada 1',
                'username' => 'armada1',
                'password' => Hash::make('123456'),
                'role' => 'armada',
            ],

            [
                'name' => 'Armada 2',
                'username' => 'armada2',
                'password' => Hash::make('123456'),
                'role' => 'armada',
            ],

            [
                'name' => 'Armada 3',
                'username' => 'armada3',
                'password' => Hash::make('123456'),
                'role' => 'armada',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
