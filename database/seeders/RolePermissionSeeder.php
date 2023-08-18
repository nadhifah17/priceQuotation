<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $manager = Role::findByName('manager');
        // if ($manager) {
        //     $manager->delete();
        // }
        // $supervisor = Role::findByName('supervisor');
        // if ($supervisor) {
        //     $supervisor->delete();
        // }
        // $staff = Role::findByName('staff');
        // if ($staff) {
        //     $staff->delete();
        // }

        // $manage_setting = Permission::findByName('manage-setting');
        // if ($manage_setting) {
        //     $manage_setting->delete();
        // }
        // $create_material = Permission::findByName('create-material');
        // if ($create_material) {
        //     $$create_material->delete();
        // }
        // $upload_material = Permission::findByName('update-material');
        // if ($upload_material) {
        //     $upload_material->delete();
        // }
        // $show_material = Permission::findByName('show-material');
        // if ($show_material) {
        //     $show_material->delete();
        // }
        // $delete_material = Permission::findByName('delete-material');
        // if ($delete_material) {
        //     $delete_material->delete();
        // }
        // $update_price = Permission::findByName('update-price');
        // if ($update_price) {
        //     $update_price->delete();
        // }

        $manager = Role::findOrCreate('manager');
        $spv = Role::findOrCreate('supervisor');
        $staff = Role::findOrCreate('staff');

        $manageSetting = Permission::findOrCreate('manage-setting');


        // assign permission to role
        $user = User::where('email', 'admin@gmail.com')->first();
        $allPermission = Permission::all();
        foreach ($allPermission as $permission) {
            $manager->givePermissionTo($permission);
        }
        //asign role to dummy user
        $user->assignRole('manager');
    }
}
