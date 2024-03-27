<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Олег Полонец',
            'email' => 'admin@admin.com',
            'password' => '1029QPwo'
        ]);

        $admin->roles()->attach(Role::where('name', 'Администратор')->first());
    }
}
