<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //ADMIN USER CONTROL
        $adminUserMail = env('ADMIN_USER_MAIL','user@admin.com');
        $adminUserPassword = env('ADMIN_USER_PASSWORD','123');
        $adminUserName = env('ADMIN_USER_NAME','Admin User');
        
        $user = User::where(['email'=>$adminUserMail])->first();
        if (!$user) {
            $user = new User;
            $user->email = $adminUserMail;
            $user->name = $adminUserName;
            $user->password = bcrypt($adminUserPassword);
            $user->saveQuietly();

            //CREATE FAKE USERS
            User::factory(10)->create();
        }

        //GET SUPER ADMIN ROLE
        $role = Role::where([
            'name'       => 'super_admin',
            'guard_name' => 'sanctum',
        ])->first();

        // CREATE ROLE IF NOT EXIST ROLE 
        if (!$role) {
            $role = new Role;
            $role->name = 'super_admin';
            $role->guard_name = 'sanctum';
            $role->saveQuietly();
        }

        //ASSIGNED ROLE FOR ADMIN
        $user->assignRole('super_admin');

    }
}
