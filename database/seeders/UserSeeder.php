<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash as FacadesHash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1, 
                'username' => 'admin',
                'nama' => 'Administrator',
                'password' => FacadesHash::make('12345'),
                'created_at' => NOW()
            ],
            [
                'user_id' => 2,
                'level_id' => 2, 
                'username' => 'manager',
                'nama' => 'Manager',
                'password' => FacadesHash::make('12345'),
                'created_at' => NOW()
            ],
            [
                'user_id' => 3,
                'level_id' => 3, 
                'username' => 'staff',
                'nama' => 'Staff/Kasir',
                'password' => FacadesHash::make('12345'),
                'created_at' => NOW()
            ]
        ];

        DB::table('m_user')->insert($data);
    }
}
