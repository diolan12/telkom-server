<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    static $name = 'accounts';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(self::$name)->insert([
            'nik' => '16982818',
            'email' => 'bosandre30@gmail.com',
            'name' => 'Super Administrator',
            'gender' => 'male',
            'phone' => '087888165849',
            'whatsapp' => '6287888165849',
            'photo' => 'mrpudidi.jpeg',
            'password' => Hash::make('12345678'),
            'role' => 0,
            'created_at' => Carbon::now('UTC')
        ]);
    }
}
