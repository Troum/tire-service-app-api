<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    const PERMISSIONS = [
        'all',
        'orders.all',
        'orders.create'
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(static::PERMISSIONS)->each(function ($permission) {
            Permission::factory()->create([
                'name' => $permission
            ]);
        });
    }
}
