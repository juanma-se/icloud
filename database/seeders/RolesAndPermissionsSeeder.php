<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $this->createPermissions(User::PERMISSIONS);

        // create roles and assign existing permissions
        $admin = Role::create(['name' => User::ADMIN_ROLE]);
        $responsible = Role::create(['name' => User::RESPONSIBLE_ROLE]);
        $assign = Role::create(['name' => User::ASSIGN_ROLE]);

        // assign permissions to roles
        $this->assignPermissions();


        // create demo users
        $admin_user = \App\Models\User::factory()->create([
            'name' => 'Example Administrator User',
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);
        $admin_user->assignRole($admin);

        $responsable_user = \App\Models\User::factory()->create([
            'name' => 'Example Responsible User',
            'email' => 'responsible@example.com',
            'password' => 'password'
        ]);
        $responsable_user->assignRole($responsible);

        $assgin_user = \App\Models\User::factory()->create([
            'name' => 'Example Assing User',
            'email' => 'assign@example.com',
            'password' => 'password'
        ]);
        $assgin_user->assignRole($assign);
    }

    private function createPermissions(array $permissions): Void
    {
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    private function assignPermissions(): Void
    {
        $roles = User::COMPOSE_PERMISSIONS_AND_ROLES;

        foreach ($roles as $role_name => $permissions) {
            $role = Role::findByName($role_name);

            foreach ($permissions as $permission_name) {
                $permission = Permission::findByName($permission_name);
                $role->givePermissionTo($permission);
            }
        }
    }

}
