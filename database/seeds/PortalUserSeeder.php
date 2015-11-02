<?php

use App\PortalUser;
use App\WatchingList;
use Illuminate\Database\Seeder;

class PortalUserSeeder extends Seeder
{

    public function run()
    {
        DB::table('portal_users')->delete();
        PortalUser::create([
            'email' => 'admin@email.com',
            'password' => bcrypt('123456'),
            'first_name' => 'Lorem',
            'last_name' => 'Ipsum',
            'role' => 'Admin',
        ]);

        PortalUser::create([
            'email' => 'adsmanager@email.com',
            'password' => bcrypt('123456'),
            'first_name' => 'Lorem',
            'last_name' => 'Ipsum',
            'role' => 'Ads_Manager',
        ]);

        PortalUser::create([
            'email' => 'minhdao.bui123@gmail.com',
            'password' => bcrypt('123456'),
            'first_name' => 'Minh Đạo',
            'last_name' => 'Bùi',
            'role' => 'Admin',
        ]);

        PortalUser::create([
            'email' => 'khanhhuy1416@gmail.com',
            'password' => bcrypt('123456'),
            'first_name' => 'Khánh Huy',
            'last_name' => 'Vũ',
            'role' => 'Ads_Manager',
        ]);
    }

}
