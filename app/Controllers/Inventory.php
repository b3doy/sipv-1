<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Konverter;
use App\Models\InventoryModel;
use App\Models\KategoriModel;
use App\Models\SatuanModel;
use App\Models\SupplierModel;
use Config\Services;

date_default_timezone_set('Asia/Jakarta');

class Inventory extends BaseController
{
    protected $inventoryModel, $konverter, $supplierModel, $kategoriModel, $satuanModel;

    function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->konverter = new Konverter();
        $this->supplierModel = new SupplierModel();
        $this->kategoriModel = new KategoriModel();
        $this->satuanModel = new SatuanModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'SIPv-1.0 || Inventory',
            'inventory' => $this->inventoryModel->getInventory(),
            'konverter' => $this->konverter,
            'supplier' => $this->supplierModel->getSupplier(),
            'kategori' => $this->kategoriModel->getKategori(),
            'satuan' => $this->satuanModel->getSatuan(),
            'kat'   => $this->kategoriModel->getKategoriTest(),
            'sat'   => $this->satuanModel->getSatuanTest(),
        ];
        return view('inventory/index', $data);
    }

    public function batal()
    {
?>
        <script>
            alert('Data KATEGORI atau Data SATUAN Masih Kosong! \n Silahkan Input Terlebih Dahulu Pada Menu KATEGORI dan SATUAN ! ');
            window.location.href = "<?= base_url('/inventory/index'); ?>"
        </script>
    <?php
    }

    public function modalTambahInventory()
    {
    ?>
        <script src="<?= base_url(); ?>/public/assets/js/jquery-3.0.6.js"></script>
        <script>
            $(document).ready(function() {
                // if (confirm('TAMBAH DATA INVENTORY / BARANG ?') == true) {
                $("confirm('TAMBAH DATA INVENTORY / BARANG ?')").on('click', function() {
                    $('#tambah-Inventory').modal('show')
                    window.location.href = "<?= base_url('/inventory/index'); ?>"
                })

            })
        </script>
        <?php
    }

    public function inventoryTable()
    {
        $list = $this->inventoryModel->getInventoryTable();
        $data = [];
        $no = 0;
        foreach ($list as $list) {
            if ($list->deleted_at == null) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->plu;
                $row[] = $list->nama_barang;
                $row[] = $list->barcode;
                $row[] = $this->konverter->rupiah($list->harga_jual);
                $row[] = $this->konverter->angka($list->stok);
                $row[] = '<a href="' . base_url('inventory/detail/' . $list->id) . '"class="btn btn-sm btn-info" style="color:black"><i class="fa fa-book"></i> Detail</a>';

                $data[] = $row;
            }
        }
        $output = ['data' => $data];
        echo json_encode($output);
    }

    public function save()
    {
        $sql = $this->inventoryModel->save([
            'kategori_id'      => $this->request->getVar('kategori'),
            'plu'           => $this->request->getVar('plu'),
            'nama_barang'   => $this->request->getVar('nama_barang'),
            'barcode'       => $this->request->getVar('barcode'),
            'harga_beli'    => $this->konverter->des($this->request->getVar('harga_beli')),
            'margin'        => $this->request->getVar('margin'),
            'harga_jual'    => $this->konverter->des($this->request->getVar('harga_jual')),
            'stok'          => $this->request->getVar('stok'),
            'kode_supplier' => $this->request->getVar('kode_supplier'),
            'satuan_id'        => $this->request->getVar('satuan')
        ]);
        if ($sql) {
        ?>
            <script>
                alert('Data Inventory Berhasil Ditambahkan!')
                window.location.href = "<?= base_url('inventory/index'); ?>"
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('Gagal Menambah Data Inventory!')
                window.location.href = "<?= base_url('inventory/index'); ?>"
            </script>
        <?php
        }
    }

    public function detail($id)
    {
        $data = [
            'title'     => 'Detail Inventory',
            'inventory' => $this->inventoryModel->getInventoryDetail($id),
            'konverter' => $this->konverter,
            'supplier'  => $this->supplierModel->getSupplier(),
            'kategori' => $this->kategoriModel->getKategori(),
            'satuan' => $this->satuanModel->getSatuan(),
        ];
        return view('inventory/detail', $data);
    }

    public function update($id)
    {
        $inventory = $this->inventoryModel->getInventory($id);
        $sql = $this->inventoryModel->save([
            'id'            => $id,
            'kategori_id'   => $this->request->getVar('kategori'),
            'plu'           => $this->request->getVar('plu'),
            'nama_barang'   => $this->request->getVar('nama_barang'),
            'barcode'       => $this->request->getVar('barcode'),
            'harga_beli'    => $this->konverter->des($this->request->getVar('harga_beli')),
            'margin'        => $this->request->getVar('margin'),
            'harga_jual'    => $this->konverter->des($this->request->getVar('harga_jual')),
            'stok'          => $this->request->getVar('stok'),
            'kode_supplier' => $this->request->getVar('kode_supplier'),
            'satuan_id'     => $this->request->getVar('satuan')
        ]);
        if ($sql) {
        ?>
            <script>
                alert('Data Inventory Berhasil Diupdate!')
                window.location.href = '<?= base_url('/inventory/detail/' . $inventory['id']); ?>'
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('Gagal Update Data Inventory!')
                window.location.href = "<?= base_url('/inventory/detail/' . $inventory['id']); ?>"
            </script>
        <?php
        }
    }

    public function delete($id)
    {
        $sql = $this->inventoryModel->delete($id);
        if ($sql) {
        ?>
            <script>
                alert('Data Inventory Berhasil Dihapus!')
                window.location.href = "<?= base_url('inventory/index'); ?>"
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('Gagal Menghapus Data Inventory!')
                window.location.href = "<?= base_url('inventory/index'); ?>"
            </script>
<?php
        }
    }
}
