<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $repo = new UserRepository();

        $user = $repo->getUserByUsername('admin');
        if (empty($user)) {
            DB::table('users')
                ->insert([
                    'name' => 'Admin',
                    'username' => 'admin',
                    'password' => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        $currencies = ['usd', 'jpy', 'eur', 'sgd'];
        foreach ($currencies as $c) {
            $currency = DB::table('currencies')
                ->where('name', $c)
                ->first();
            if (empty($currency)) {
                DB::table('currencies')
                    ->insert([
                        'name' => $c,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }
        }
    }
}
