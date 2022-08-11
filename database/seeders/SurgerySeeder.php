<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\Surgery;
use Illuminate\Support\Str;
class SurgerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         for ($i=0; $i < 30; $i++) { 
	    	Surgery::create([
	            'name' => Str::random(10),
                'email' => Str::random(10).'@gmail.com',
	        ]);
    	}
    }
}
