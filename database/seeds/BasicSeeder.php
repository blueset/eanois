<?php

use Illuminate\Database\Seeder;

class BasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Configurations
         */

        DB::table('config')->insert([
            "key" => "site_name",
            "value" => "1A23 Studio",
        ]);
        DB::table('config')->insert([
            "key" => "theme",
            "value" => "default",
        ]);
        DB::table('config')->insert([
            "key" => "admin_theme",
            "value" => "default_admin",
        ]);
        DB::table('config')->insert([
            "key" => "feed_url",
            "value" => "",
        ]);

        /*
         * User
         */

        DB::table('users')->insert([
            "name" => "admin",
            "email" => "admin@example.com",
            "password" => bcrypt("password"),
        ]);

    }
}
