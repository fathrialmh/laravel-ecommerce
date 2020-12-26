<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => "Fathrial Muhamad",
        //     'email' => "fathrialmuhamad@gmail.com",
        //     'password' => bcrypt('password'),
        // ]);
        
        User::create([
            'name' => 'Fathrial Muhamad',
            'email' => 'fathrialmuhamad@gmail.com',
            'password' => bcrypt('secret')
        ]);
    }
}
