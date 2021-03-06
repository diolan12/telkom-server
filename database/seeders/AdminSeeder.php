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
            'email' => 'dio_lantief21@outlook.com',
            'name' => 'Dio Lantief Widoyoko',
            'gender' => 'male',
            'phone' => '085648535927',
            'whatsapp' => '6285648535927',
            'photo' => 'default.jpg',
            'password' => Hash::make('12345678'),
            'role' => 0,
            'created_at' => Carbon::now('UTC')
        ]);
    }
}
