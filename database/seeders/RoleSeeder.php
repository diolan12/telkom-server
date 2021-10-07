<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    static $name = 'roles';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(self::$name)->insert([
            'level' => 0,
            'name' => 'admin'
        ]);
        DB::table(self::$name)->insert([
            'level' => 1,
            'name' => 'office'
        ]);
        DB::table(self::$name)->insert([
            'level' => 2,
            'name' => 'field'
        ]);
    }
}
