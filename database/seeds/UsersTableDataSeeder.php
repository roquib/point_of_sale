<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => "Asm",
            'last_name'  => "Akash",
            'email'      => "akashcseuu026@gmail.com",
            'password'   => Hash::make("123456789"),
        ]);
        User::create([
            'first_name' => "Abdur",
            'last_name'  => "Roquib",
            'email'      => "roquib01@gmail.com",
            'password'   => Hash::make("roquib123"),
        ]);
    }
}
