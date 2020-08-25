<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => 'thamer.belfkih@gmail.com',
            'password' => bcrypt('thamerbelfkih'),
            'created_at' => Carbon::now()
        ]);
    }
}
