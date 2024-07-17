<?php

use App\Models\User;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name'  => 'Richard',
                'last_name'   => 'Stenson',
                'email'       => 'rstenson@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Superadmin',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'superadmin',
            ],
            [
                'first_name'  => 'Ben',
                'last_name'   => 'Grout',
                'email'       => 'bgrout@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Superadmin',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'superadmin',
            ],
            [
                'first_name'  => 'Mukesh',
                'last_name'   => 'Tilokani',
                'email'       => 'mtilokani@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Superadmin',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'superadmin',
            ],
            [
                'first_name'  => 'Usama',
                'last_name'   => 'Patel',
                'email'       => 'upatel@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Clubadmin',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'clubadmin',
            ],
            [
                'first_name'  => 'Rishabh',
                'last_name'   => 'Shah',
                'email'       => 'rshah@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Staff',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'staff',
            ],
            [
                'first_name'  => 'Sunny',
                'last_name'   => 'Sheth',
                'email'       => 'ssheth@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Consumer',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'consumer',
            ],
            [
                'first_name'  => 'Anjali',
                'last_name'   => 'Soni',
                'email'       => 'asoni@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Consumer',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'consumer',
            ],
            [
                'first_name'  => 'Pratik',
                'last_name'   => 'Patel',
                'email'       => 'ppatel@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Consumer',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'consumer',
            ],
            [
                'first_name'  => 'Ankit',
                'last_name'   => 'Chauhan',
                'email'       => 'achauhan@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Staff',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'staff',
            ],
            [
                'first_name'  => 'Indrajit',
                'last_name'   => 'Rathod',
                'email'       => 'irathod@aecordigital.com',
                'is_verified' => 1,
                'type'        => 'Staff',
                'status'      => 'Active',
                'password'    => bcrypt('password'),
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'roles'       => 'staff',
            ],
        ];
        DB::table('users')->delete();
        foreach ($users as $index=>$u) {
            $user = User::create(Arr::except($u, ['roles']));
            $user->syncRoles([$u['roles']]);
            if ($u['roles'] == 'superadmin' || $u['roles'] == 'clubadmin') {
                DB::table('cms')->insert([
                    [
                        'user_id'    => $index + 1,
                        'club_id'    => 1,
                        'company'    => 'Aecor',
                        'notes'	     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]);
            }
            if ($u['roles'] == 'staff') {
                DB::table('staff')->insert([
                    [
                        'user_id'    => $index + 1,
                        'club_id'    => 1,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]);
            }
            if ($u['roles'] == 'consumer') {
                DB::table('consumers')->insert([
                    [
                        'user_id'       => $index + 1,
                        'club_id'       => 12,
                        'date_of_birth' => '1992-12-25',
                        'settings'      => '{"is_notification_enabled": "true"}',
                        'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ]);
            }
        }
    }
}
