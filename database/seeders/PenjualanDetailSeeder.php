<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_penjualan_detail')->insert([
            ['penjualan_id' => 1, 'barang_id' => 7, 'harga' => 7000000, 'jumlah' => 1, 'created_at' => NOW()],
            ['penjualan_id' => 1, 'barang_id' => 8, 'harga' => 4500000, 'jumlah' => 1, 'created_at' => NOW()],
            ['penjualan_id' => 2, 'barang_id' => 9, 'harga' => 150000, 'jumlah' => 2, 'created_at' => NOW()],
            ['penjualan_id' => 3, 'barang_id' => 10, 'harga' => 300000, 'jumlah' => 1, 'created_at' => NOW()],
        ]);
    }
}
