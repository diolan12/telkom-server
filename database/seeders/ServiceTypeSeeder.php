<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceTypeSeeder extends Seeder
{
    static $name = 'service_types';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(self::$name)->insert([
            'name' => 'Regular',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'name' => 'Add-On',
            'created_at' => Carbon::now('UTC')
        ]);
    }
}
