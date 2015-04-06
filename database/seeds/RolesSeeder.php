<?php
use App\Models\Role;
use Illuminate\Database\Seeder; 

class RolesSeeder extends Seeder {
    
    public function run() {
        Role::create([
            'role' => 'admin'
        ]);
        
        Role::create([
            'role' => 'user'
        ]);
    }
    
}