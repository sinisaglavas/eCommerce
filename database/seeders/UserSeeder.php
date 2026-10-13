<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Demo Administrator', 'admin@voltio.test', true],
            ['Ana Petrović', 'ana@voltio.test', false],
            ['Marko Jovanović', 'marko@voltio.test', false],
            ['Jelena Nikolić', 'jelena@voltio.test', false],
        ] as [$name, $email, $admin]) {
            User::query()->firstOrCreate(['email' => $email], [
                'name' => $name, 'password' => Hash::make('VoltioDemo!2026'),
                'is_admin' => $admin, 'email_verified_at' => now(),
            ]);
        }
    }
}
