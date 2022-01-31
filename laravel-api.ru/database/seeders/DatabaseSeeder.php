<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meeting;
use App\Models\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Meeting::factory(100)->create();
        // \App\Models\User::factory(10)->create();
        Employee::factory(100)->create();
    }
}
