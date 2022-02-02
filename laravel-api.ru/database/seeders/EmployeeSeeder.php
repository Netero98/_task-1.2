<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
   /**
    * Seed the application's database.
    *
    * @return void
    */
   public function run() {
      Employee::factory(50)->create();
   }
}
