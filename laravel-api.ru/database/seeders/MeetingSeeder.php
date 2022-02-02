<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meeting;

class MeetingSeeder extends Seeder
{
   /**
    * Seed the application's database.
    *
    * @return void
    */
   public function run() {
      Meeting::factory(50)->create();
   }
}
