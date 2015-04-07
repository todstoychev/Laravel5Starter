<?php
use App\Models\Role;
use Illuminate\Database\Seeder; 

class RolesSeeder extends Seeder {
    
    public function run() {
       $roles = ['admin', 'user'];
       
       foreach ($roles as $role) {
           $instance = new Role(['role' => $role]);
           $instance->save();
           $instance->addSearchIndex();
       }
    }
    
}