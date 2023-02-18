<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder {
    use TruncateTable, DisableForeignKeys;

    public function run() : void {

        // Owner
        $owner = [
            'role_key' => 'super-admin',
            'first_name' => 'maurice',
            'last_name' => 'yabre',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('password'), // password
            'phone' => '+393211234567',
            'birthday' => '1992-09-22',
            'description' => Factory::create()->realText(),
            'remember_token' => Str::random(10),
            'iban' => fake()->bankAccountNumber(),
            'tax_id' => fake()->taxId(),
            'vat_id' => fake()->vat(),
            'last_login' => now()->subHour(),
            'hire_date' => now()->subMonths(24)
        ];
        User::create($owner);

        // Admin
        $admin = [
            'role_key' => 'admin',
            'first_name' => 'stefano',
            'last_name' => 'mondini',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'), // password
            'phone' => '+393211234567',
            'birthday' => '1989-10-22',
            'description' => Factory::create()->realText(),
            'remember_token' => Str::random(10),
            'iban' => fake()->bankAccountNumber(),
            'tax_id' => fake()->taxId(),
            'vat_id' => fake()->vat(),
            'last_login' => now()->subDays(rand(1, 7))->subHours(rand(1, 12)),
            'hire_date' => now()->subMonths(24)
        ];
        User::create($admin);

        // Employee
        $designer = [
            'role_key' => 'designer',
            'first_name' => 'vanessa',
            'last_name' => 'cauzzi',
            'email' => 'employee@email.com',
            'password' => Hash::make('password'), // password
            'phone' => '+393211234567',
            'birthday' => '1997-09-05',
            'description' => Factory::create()->realText(),
            'remember_token' => Str::random(10),
            'iban' => fake()->bankAccountNumber(),
            'tax_id' => fake()->taxId(),
            'vat_id' => fake()->vat(),
            'last_login' => now()->subDays(rand(1, 7))->subHours(rand(1, 12)),
            'hire_date' => now()->subMonths(18)
        ];
        User::create($designer);

        // Create Users
        User::factory()->count(8)->create();

        $users = User::all();

        // Create Addresses
        $users->each(function ($user) {
            $user->address()->create([
                'street' => fake()->streetAddress(),
                'postcode' => fake()->postcode(),
                'city' => fake()->city(),
                'country_code' => 'IT',
                'state' => null,
                'province' => fake()->stateAbbr(),
                'addressable_type' => User::class,
                'addressable_id' => $user->id
            ]);
        });

        $users->sortByDesc('id')->take(5)->each(function ($user) {
            $user->delete();
        });

        User::onlyTrashed()->first()->forceDelete();
    }
}
