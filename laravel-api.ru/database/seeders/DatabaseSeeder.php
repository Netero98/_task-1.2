<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meeting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $meetings = Meeting::factory(10)->make();
        dd($meetings);
        // \App\Models\User::factory(10)->create();
    }
}
