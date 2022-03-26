<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            "employee_name" => "admin",
            "employee_no" => "5000",
            "role" => "Admin",
            "password" => "123123",
        ]);
        User::create([
            "employee_name" => "employee",
            "employee_no" => "1000",
            "role" => "Employee",
            "password" => "123123",
        ]);
    }
}
