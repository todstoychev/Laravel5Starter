<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder {

    public function run() {
        $faker = Faker\Factory::create();

        $user = new User();
        $user->username = 'admin';
        $user->password = Hash::make('admin');
        $user->email = 'admin@domain.com';
        $user->deleted_at = null;
        $user->confirmed_at = \Carbon\Carbon::now();
        $user->save();

        $user->roles()->save(Role::find(1));
        
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->username = $faker->userName;
            $user->password = Hash::make('password');
            $user->email = $faker->safeEmail;
            $user->deleted_at = null;
            $user->confirmed_at = \Carbon\Carbon::now();
            $user->save();

            $user->roles()->save(Role::find(2));
        }
    }

}
