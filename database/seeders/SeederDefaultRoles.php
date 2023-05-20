<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeederDefaultRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(\App\Models\Role::$roles as $role) {
            \App\Models\Role::create([
                'role_name' => $role,
            ]);
        }
    }
}
