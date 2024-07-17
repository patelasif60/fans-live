<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'superadmin', 'display_name' => 'superadmin']);
        Role::create(['name' => 'clubadmin', 'display_name' => 'clubadmin']);
        Role::create(['name' => 'staff', 'display_name' => 'staff']);
        Role::create(['name' => 'consumer', 'display_name' => 'consumer']);
    }
}
