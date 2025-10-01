<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $perms = [
            'users.view','users.create','users.update','users.delete',
            'projects.view','projects.create','projects.update','projects.delete',
        ];
        foreach ($perms as $p) Permission::firstOrCreate(['name'=>$p]);

        $owner  = Role::firstOrCreate(['name'=>'owner']);
        $admin  = Role::firstOrCreate(['name'=>'admin']);
        $member = Role::firstOrCreate(['name'=>'member']);
        $viewer = Role::firstOrCreate(['name'=>'viewer']);

        $owner->givePermissionTo($perms);
        $admin->givePermissionTo($perms);
        $member->givePermissionTo(['projects.view']);
        $viewer->givePermissionTo(['projects.view']);
    }
}
