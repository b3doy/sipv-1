<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'inventory';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'plu', 'nama_barang', 'barcode', 'harga_beli', 'margin', 'harga_jual', 'stok', 'kode_supplier', 'kategori_id', 'satuan_id'
    ];

    public function getInventory($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->find($id);
    }

    public function setCode()
    {
        return $this->db->table('inventory')->selectMax('plu')->get()->getRowArray()['plu'];
    }

    public function getInventoryTable()
    {
        return $this->db->table('inventory')->select('*')->get()->getResult();
    }

    public function getInventoryDetail($id)
    {
        $builder = $this->db->table('inventory')->select('inventory.id as inventory_id, plu, nama_barang, barcode, harga_beli, margin, harga_jual, inventory.kode_supplier as inv_kode_supplier, stok, kategori_id, satuan_id, nama_kategori, nama_satuan, nama_supplier');
        $builder->join('supplier', 'supplier.kode_supplier = inventory.kode_supplier');
        $builder->join('kategori', 'kategori.id = inventory.kategori_id');
        $builder->join('satuan', 'satuan.id = inventory.satuan_id');
        $builder->where('inventory.id', $id);
        $query = $builder->get()->getRowObject();
        return $query;
    }

    public function cariBarangTable($keyword_barcode)
    {
        if (strlen($keyword_barcode) == 0) {
            return $this->db->table('inventory')->select('*')->join('kategori', 'kategori.id=inventory.kategori_id')->get()->getResult();
        } else {
            return $this->db->table('inventory')->select('*')->join('kategori', 'kategori.id=inventory.kategori_id')->like('barcode', $keyword_barcode)->orLike('nama_barang', $keyword_barcode)->orLike('plu', $keyword_barcode)->get()->getResult();
        }
    }

    public function cekBarang($barcode)
    {
        return $this->table('inventory')->like('barcode', $barcode)->orLike('nama_barang', $barcode)->orLike('plu', $barcode)->get();
    }
}
