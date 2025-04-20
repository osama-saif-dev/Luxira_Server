<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            User::create([
                'id' => $i,
                'first_name' => 'firstName' . $i,
                'last_name' => 'lastName' . $i,
                'email' =>  "osamasaif24$i@gmail.com",
                'password' => Hash::make('osama3546'),
                'gender' => 'm',
                'phone' => "20109021524$i"
            ]);
        }
        for ($i = 8; $i < 15; $i++) {
            User::create([
                'id' => $i,
                'first_name' => 'firstName' . $i,
                'last_name' => 'lastName' . $i,
                'email' =>  "osamasaif24$i@gmail.com",
                'password' => Hash::make('osama3546'),
                'gender' => 'f',
                'phone' => "20109021524$i",
                'role' => 'admin'
            ]);
        }
    }
}
