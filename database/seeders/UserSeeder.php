<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // permission methods
        $permission_data = ['create','view','update','delete'];
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions and menus key from config file
        $templates = array_values(config('menu.templates'));
        foreach ($permission_data as $permissionRow){
            foreach ($templates as $row){
                $templateKey  = str_replace('_',' ',$row['key']);
                Permission::create([
                    'name'=>"{$permissionRow} {$templateKey}",
                    'group'=>'',
                    'guard_name'=>'web',
                ]);
            }
        }
        // create roles and assign created permissions
        $role = Role::create([
            'name'=>'super_admin',
            'guard_name'=>'web',
        ]);
        $role->givePermissionTo(Permission::all());

        // Create User
        User::factory()->count(1)->create([
            'id'=>1,
            'name' => 'super admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('Test7777@')
        ])->each(function ($user){
            $user->assignRole('super_admin');
        });
    }
}
