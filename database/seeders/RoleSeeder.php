<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::factory()->create(['name' => 'Администратор']);
        $employee = Role::factory()->create(['name' => 'Рабочий']);

        $admin->permissions()->attach(Permission::all());

        $employee->permissions()->attach(Permission::where('name', 'like', '%orders%')->first());
    }
}
