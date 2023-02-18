<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
    public function run() {
        $roles = collect([
            [
                'name' => 'Super Admin',
                'key' => 'super-admin',
                'description' => 'Ha accesso a tutti gli aspetti del software.'
            ],
            [
                'name' => 'Admin',
                'key' => 'admin',
                'description' => 'Ha accesso a tutto, ma non può modificare gli altri amministratori.'
            ],
            [
                'name' => 'Designer',
                'key' => 'designer',
                'description' => 'Può modificare il proprio profilo, vedere e gestire i progetti che gli vengono assegnati.'
            ],
            [
                'name' => 'Analista',
                'key' => 'accountant',
                'description' => 'Può modificare il proprio profilo, vedere e gestire le finanze.'
            ]
        ]);
        $roles->each(function($role) {
            Role::create($role);
        });
    }
}
