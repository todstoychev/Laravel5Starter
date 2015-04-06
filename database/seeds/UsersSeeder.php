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
        $user->email = 'todstoychev@gmail.com';
        $user->deleted_at = null;
        $user->confirmed_at = \Carbon\Carbon::now();
        $user->save();

        $user->roles()->save(Role::find(1));
        $user->addSearchIndex();

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->username = $faker->userName;
            $user->password = Hash::make('password');
            $user->email = $faker->safeEmail;
            $user->deleted_at = null;
            $user->confirmed_at = \Carbon\Carbon::now();
            $user->save();
            
            $user->roles()->save(Role::find(2));
            $user->addSearchIndex();
        }
    }

}
