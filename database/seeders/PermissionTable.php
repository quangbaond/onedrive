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
                'name' => 'Xem danh sách người dùng',
                'permission' => 'view user',
                'guard_name' => 'web',
                'description' => 'view user',
            ],
            [
                'name' => 'Tạo người dùng',
                'permission' => 'create user',
                'guard_name' => 'web',
                'description' => 'Create user',
            ],
            [
                'name' => 'Cập nhật người dùng',
                'permission' => 'update user',
                'guard_name' => 'web',
                'description' => 'Update user',
            ],
            [
                'name' => 'Phân quyền người dùng',
                'permission' => 'assign role user',
                'guard_name' => 'web',
                'description' => 'Assign role user',
            ],
            [
                'name' => 'Xóa người dùng',
                'permission' => 'delete user',
                'guard_name' => 'web',
                'description' => 'Delete user',
            ],
            [
                'name' => 'Xem danh sách nhóm',
                'permission' => 'view group',
                'guard_name' => 'web',
                'description' => 'view group',
            ],
            [
                'name' => 'Tạo nhóm',
                'permission' => 'create group',
                'guard_name' => 'web',
                'description' => 'Create group',
            ],
            [
                'name' => 'Cập nhật nhóm',
                'permission' => 'update group',
                'guard_name' => 'web',
                'description' => 'Update group',
            ],
            [
                'name' => 'Xóa nhóm',
                'permission' => 'delete group',
                'guard_name' => 'web',
                'description' => 'Delete group',
            ],
            [
                'name' => 'Gúi email',
                'permission' => 'send email',
                'guard_name' => 'web',
                'description' => 'Send email',
            ],
            [
                'name' => 'Xem danh sách cv',
                'permission' => 'view cv',
                'guard_name' => 'web',
                'description' => 'view cv',
            ],
            [
                'name' => 'Tạo cv',
                'permission' => 'create cv',
                'guard_name' => 'web',
                'description' => 'Create Cv',
            ],
            [
                'name' => 'Cập nhật cv',
                'permission' => 'update cv',
                'guard_name' => 'web',
                'description' => 'Update Cv',
            ],
            [
                'name' => 'Xóa cv',
                'permission' => 'delete cv',
                'guard_name' => 'web',
                'description' => 'Delete Cv',
            ],
            [
                'name' => 'Xem danh sách bài viết',
                'permission' => 'view post',
                'guard_name' => 'web',
                'description' => 'view post',
            ],
            [
                'name' => 'Tạo bài viết',
                'permission' => 'create post',
                'guard_name' => 'web',
                'description' => 'Create post',
            ],
            [
                'name' => 'Cập nhật bài viết',
                'permission' => 'update post',
                'guard_name' => 'web',
                'description' => 'Update post',
            ],
            [
                'name' => 'Xóa bài viết',
                'permission' => 'delete post',
                'guard_name' => 'web',
                'description' => 'Delete post',
            ]
        ];

        foreach ($permission as $key => $value) {
            Permission::query()->create($value);
        }
    }
}
