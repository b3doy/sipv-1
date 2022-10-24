<?php

namespace App\Models;

use App\Controllers\Inventory;
use CodeIgniter\Model;

class PenjualantempModel extends Model
{
    protected $table = 'penjualan_temp';
    protected $allowedFields = [
        'faktur_penjualan_detail', 'barcode_penjualan_detail', 'harga_beli_penjualan_detail', '	harga_jual_penjualan_detail',
        'jumlah_penjualan_detail', 'sub_total_penjualan_detail'
    ];

    public function getPenjualantemp($fakturPenjualan = false)
    {
        if ($fakturPenjualan == false) {
            return $this->findAll();
        }
        return $this->find($fakturPenjualan);
    }

    public function getPenjualantempDetail($fakturPenjualan)
    {
        return $this->db->table('penjualan_temp')->select('penjualan_temp.id as temp_id, barcode_penjualan_detail as kode_barcode, harga_jual_penjualan_detail as harga, jumlah_penjualan_detail as jml, sub_total_penjualan_detail as sub_total, nama_barang')->join('inventory', 'inventory.barcode = penjualan_temp.barcode_penjualan_detail')->where('faktur_penjualan_detail', $fakturPenjualan)->orderBy('penjualan_temp.id')->get()->getResultArray();
    }

    public function totalBayar($fakturPenjualan)
    {
        return $this->db->table('penjualan_temp')->select('SUM(sub_total_penjualan_detail) as total_bayar')->where('faktur_penjualan_detail', $fakturPenjualan)->get();
    }

    public function hapusTransaksi()
    {
        return $this->db->table('penjualan_temp')->emptyTable();
    }

    public function simpanTransaksi($fakturPenjualan)
    {
        return $this->table('penjualan_temp')->getWhere(['faktur_penjualan_detail' => $fakturPenjualan]);
    }

    public function ambilDataTemp($faktur_penjualan)
    {
        return $this->db->table('penjualan_temp')->getWhere(['faktur_penjualan_detail' => $faktur_penjualan]);
    }
}
