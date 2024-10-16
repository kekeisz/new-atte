<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '磯崎圭佑',
            'email' => 'kekeisozaki13@gmail.com',
            'password' => 'Imkekeisz',
            'email_verified_at' => '20240925'
        ];
        DB::table('users')->insert($param);
    }
}
