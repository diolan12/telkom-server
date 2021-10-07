<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    static $name = 'services';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Layanan Reguler
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Internet (20Mbps)',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Internet (30Mbps)',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Internet (50Mbps)',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Internet (100Mbps)',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Internet (200Mbps)',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Internet (500Mbps)',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang Telepon',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 1,
            'name' => 'Pasang TV Kabel',
            'created_at' => Carbon::now('UTC')
        ]);

        // Layanan Add-On
        DB::table(self::$name)->insert([
            'type' => 2,
            'name' => 'Tambah TV Kabel',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 2,
            'name' => 'Tambah PLC + TV',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 2,
            'name' => 'Tambah Extender',
            'created_at' => Carbon::now('UTC')
        ]);
        DB::table(self::$name)->insert([
            'type' => 2,
            'name' => 'Tambah Layanan Channel TV',
            'created_at' => Carbon::now('UTC')
        ]);
    }
}
