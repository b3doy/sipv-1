<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualandetailModel extends Model
{
    protected $table = 'penjualan_detail';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'faktur_penjualan_detail', 'barcode_penjualan_detail', 'harga_beli_penjualan_detail', 'harga_jual_penjualan_detail',
        'jumlah_penjualan_detail', 'sub_total_penjualan_detail'
    ];
}