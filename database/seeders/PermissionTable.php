<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = [
            [
                'name' => 'view user',
                'guard_name' => 'web',
                'description' => 'view user',
            ],
            [
                'name' => 'create user',
                'guard_name' => 'web',
                'description' => 'Create user',
            ],
            [
                'name' => 'update user',
                'guard_name' => 'web',
                'description' => 'Update user',
            ],
            [
                'name' => 'delete user',
                'guard_name' => 'web',
                'description' => 'Delete user',
            ],
            [
                'name' => 'view group',
                'guard_name' => 'web',
                'description' => 'view group',
            ],
            [
                'name' => 'create group',
                'guard_name' => 'web',
                'description' => 'Create group',
            ],
            [
                'name' => 'update group',
                'guard_name' => 'web',
                'description' => 'Update group',
            ],
            [
                'name' => 'delete group',
                'guard_name' => 'web',
                'description' => 'Delete group',
            ],
            [
                'name' => 'send email',
                'guard_name' => 'web',
                'description' => 'Send email',
            ],
            [
                'name' => 'view cv',
                'guard_name' => 'web',
                'description' => 'view cv',
            ],
            [
                'name' => 'create cv',
                'guard_name' => 'web',
                'description' => 'Create Cv',
            ],
            [
                'name' => 'update cv',
                'guard_name' => 'web',
                'description' => 'Update Cv',
            ],
            [
                'name' => 'delete cv',
                'guard_name' => 'web',
                'description' => 'Delete Cv',
            ],
        ];

        foreach ($permission as $key => $value) {
            Permission::query()->create($value);
        }
    }
}
