<?php

namespace App\Controllers;

use App\Libraries\Konverter;
use App\Models\InventoryModel;
use App\Models\PenjualandetailModel;
use App\Models\PenjualanModel;
use App\Models\PenjualantempModel;
use Config\Database;

class Penjualan extends BaseController
{
    protected $konverter, $penjualantempModel, $penjualanModel,
        $penjualandetailModel, $inventoryModel, $db, $builder;

    function __construct()
    {
        $this->konverter = new Konverter;
        $this->penjualantempModel = new PenjualantempModel();
        $this->inventoryModel = new InventoryModel();
        $this->penjualanModel = new PenjualanModel();
        $this->penjualandetailModel = new PenjualandetailModel();
        $this->db = Database::connect();
        $this->builder = $this->db->table('inventory');
    }

    public function index()
    {
        $data = [
            'title'     => 'Menu Penjualan'
        ];
        return view('penjualan/index', $data);
    }

    public function input()
    {
        $data = [
            'title'     => 'Input Kasir',
            'konverter' => $this->konverter,
            'penjualan' => $this->penjualantempModel->getPenjualantemp()
        ];
        return view('penjualan/input', $data);
    }

    public function detail()
    {
        $fakturPenjualan = $this->request->getVar('fakturPenjualan');

        $data = [
            'data_penjualan'    => $this->penjualantempModel->getPenjualantempDetail($fakturPenjualan),
            'konverter'         => $this->konverter
        ];
        $output = [
            'data' => view('penjualan/detail', $data)
        ];
        echo json_encode($output);
    }

    public function dataBarang()
    {
        if ($this->request->isAJAX()) {
            $keyword = $this->request->getVar('keyword');
            $cari = ['keyword' => $keyword];
            $data = [
                'view_modal' => view('penjualan/caribarang', $cari)
            ];
            echo json_encode($data);
        }
    }

    public function cariBarangTable()
    {
        $keyword_barcode = $this->request->getVar('keyword_barcode');
        $list = $this->inventoryModel->cariBarangTable($keyword_barcode);
        $data = [];
        $no = 0;
        foreach ($list as $list) {
            if ($list->deleted_at == null) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->plu;
                $row[] = $list->barcode;
                $row[] = $list->nama_barang;
                $row[] = $list->nama_kategori;
                $row[] = $this->konverter->angka($list->stok);
                $row[] = $this->konverter->rupiah($list->harga_jual);
                $row[] = "<button type=\"button\" class=\"btn btn-sm btn-outline-info\" onclick=\"pilihItem('" . $list->barcode . "','" . $list->nama_barang . "')\">Pilih</button>";

                $data[] = $row;
            }
        }
        $output = ['data' => $data];
        echo json_encode($output);
    }

    public function saveTemp()
    {
        if ($this->request->isAJAX()) {
            $barcode = $this->request->getVar('barcode');
            $nama_barang = $this->request->getVar('namaBarang');
            $jumlah = $this->request->getVar('jumlah');
            $fakturPenjualan = $this->request->getVar('fakturPenjualan');
            // $fakturPenjualan = $this->konverter->fakturJual();

            $cekBarang0 = $this->inventoryModel->cekBarang($barcode);
            $cekBarang = $cekBarang0->getNumRows();
            if ($cekBarang > 1) {
                $msg = ['cekBarang' => 'banyak'];
            } else if ($cekBarang == 1) {
                // insert data ke penjualan_temp
                $penjualanTemp = $this->db->table('penjualan_temp');
                $rowBarang = $cekBarang0->getRowArray();

                $stokBarang = $rowBarang['stok'];
                if (intval($stokBarang) == 0) {
                    $msg = [
                        'error' => 'Maaf Stok Sedang Kosong!'
                    ];
                } elseif ($jumlah > intval($stokBarang)) {
                    $msg = [
                        'error' => 'Maaf Stok tidak Mencukupi!'
                    ];
                } else {
                    $insertData = [
                        'faktur_penjualan_detail' => $fakturPenjualan,
                        'barcode_penjualan_detail' => $rowBarang['barcode'],
                        'harga_beli_penjualan_detail' => $rowBarang['harga_beli'],
                        'harga_jual_penjualan_detail' => $rowBarang['harga_jual'],
                        'jumlah_penjualan_detail' => $jumlah,
                        'sub_total_penjualan_detail' => floatval($rowBarang['harga_jual']) * $jumlah,
                    ];
                    $penjualanTemp->insert($insertData);
                    $msg = ['sukses' => 'berhasil'];
                }
            } else {
                $msg = ['error' => 'Maaf Data Barang Tidak Ditemukan!'];
            }
            echo json_encode($msg);
        }
    }

    public function hitungTotalBayar()
    {
        if ($this->request->isAJAX()) {
            $fakturPenjualan = $this->request->getVar('fakturPenjualan');
            $sqltotal = $this->penjualantempModel->totalBayar($fakturPenjualan);
            $rowTotal = $sqltotal->getRowArray();

            $data = [
                'total_bayar' => $this->konverter->rupiah($rowTotal['total_bayar'])
            ];
            echo json_encode($data);
        }
    }

    public function delete($id)
    {
        $sql = $this->penjualantempModel->delete($id);
        if ($sql) {
?>
            <script>
                alert('Data Berhasil Dihapus!')
                window.location.href = "<?= base_url('penjualan/input'); ?>"
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('Gagal Menghapus Data!')
                window.location.href = "<?= base_url('penjualan/input'); ?>"
            </script>
        <?php
        }
    }

    public function hapusTransaksi()
    {
        $sql = $this->penjualantempModel->hapusTransaksi();
        if ($sql) {
        ?>
            <script>
                alert('Data Transaksi Berhasil Dibatalkan!')
                window.location.href = "<?= base_url('penjualan/input'); ?>"
            </script>
        <?php
        } else {
        ?>
            <script>
                alert('Gagal Membatalkan Data Transaksi!')
                window.location.href = "<?= base_url('penjualan/input'); ?>"
            </script>
        <?php
        }
    }

    public function simpanTransaksi()
    {
        if ($this->request->isAJAX()) {
            $fakturPenjualan = $this->request->getVar('fakturPenjualan');
            $tanggalPenjualan = $this->request->getVar('tanggalPenjualan');
            $kodeKonsumenPenjualan = $this->request->getVar('kodeKonsumenPenjualan');

            $penjualanTemp = $this->penjualantempModel->simpanTransaksi($fakturPenjualan);

            $sqltotal = $this->penjualantempModel->totalBayar($fakturPenjualan);
            $rowTotal = $sqltotal->getRowArray();

            if ($penjualanTemp->getNumRows() > 0) {
                $data = [
                    'fakturPenjualan'       => $fakturPenjualan,
                    'kodeKonsumenPenjualan' => $kodeKonsumenPenjualan,
                    'tanggalPenjualan'      => $tanggalPenjualan,
                    'totalBayar'            => $rowTotal['total_bayar'],
                    'konverter'             => $this->konverter
                ];
                $msg = ['data' => view('penjualan/pembayaran', $data)];
            } else {
                $msg = ['error' => 'Tidak Ada Item Yang Akan Dibayar!'];
            }
            echo json_encode($msg);
        }
    }

    public function simpanPembayaran()
    {
        $faktur_penjualan = $this->request->getVar('faktur_penjualan');
        $tanggal_penjualan = $this->request->getVar('tanggal_penjualan');
        $kode_konsumen_penjualan = $this->request->getVar('kode_konsumen');
        $diskon_persen_penjualan = $this->request->getVar('diskon_persen_penjualan');
        $diskon_nominal_penjualan = $this->konverter->des($this->request->getVar('diskon_nominal_penjualan'));
        $total_kotor_penjualan = $this->request->getVar('total_kotor_penjualan');
        $total_bersih_penjualan = $this->konverter->des($this->request->getVar('total_bersih_penjualan'));
        $jumlah_uang = $this->konverter->des($this->request->getVar('jumlah_uang'));
        $sisa_uang = $this->konverter->des($this->request->getVar('sisa_uang'));

        if ($jumlah_uang == 0 || $jumlah_uang == '') {
        ?>
            <script>
                alert('Jumlah Uang Belum Diinput!')
                window.location.href = "<?= base_url('penjualan/input'); ?>"
            </script>
        <?php
        } else if ($sisa_uang < 0) {
        ?>
            <script>
                alert('Jumlah Uang Tidak Mencukupi Untuk Melakukan Pembayaran!')
                window.location.href = "<?= base_url('penjualan/input'); ?>"
            </script>
            <?php
        } else {
            // Insert ke Tabel Penjualan
            $sql = $this->penjualanModel->insert([
                'faktur_penjualan'          => $faktur_penjualan,
                'tanggal_penjualan'         => $tanggal_penjualan,
                'kode_konsumen_penjualan'   => $kode_konsumen_penjualan,
                'diskon_persen_penjualan'   => $diskon_persen_penjualan,
                'diskon_nominal_penjualan'  => $diskon_nominal_penjualan,
                'total_kotor_penjualan'     => $total_kotor_penjualan,
                'total_bersih_penjualan'    => $total_bersih_penjualan,
                'jumlah_uang'               => $jumlah_uang,
                'sisa_uang'                 => $sisa_uang,
            ]);

            // Insert ke tabel penjualan_detail
            $tblPenjualanDetail = $this->db->table('penjualan_detail');
            $ambilDataTemp = $this->penjualantempModel->ambilDataTemp($faktur_penjualan);
            $fieldPenjualanDetail = [];
            foreach ($ambilDataTemp->getResultArray() as $row) {
                $fieldPenjualanDetail[] = [
                    'faktur_penjualan_detail' => $row['faktur_penjualan_detail'],
                    'barcode_penjualan_detail' => $row['barcode_penjualan_detail'],
                    'harga_beli_penjualan_detail' => $row['harga_beli_penjualan_detail'],
                    'harga_jual_penjualan_detail' => $row['harga_jual_penjualan_detail'],
                    'jumlah_penjualan_detail' => $row['jumlah_penjualan_detail'],
                    'sub_total_penjualan_detail' => $row['sub_total_penjualan_detail'],
                ];
            }
            $tblPenjualanDetail->insertBatch($fieldPenjualanDetail);


            // Hapus dari tabel penjualan_temp
            $this->penjualantempModel->hapusTransaksi();

            if ($sql) {
            ?>
                <script>
                    if (confirm('Data Pembayaran Berhasi Disimpan \n CETAK STRUK ATAU TIDAK ?') == true) {
                        // Jika Menggunakan Printer Non Thermal dengan Print Preview
                        window.open('<?= base_url('penjualan/print/' . $faktur_penjualan); ?>', 'Print Transaksi', 'width=500,height=700')

                        // Jika Menggunakan Printer Thermal
                        // alert('PRINT !!!!!!!!!!!!!!')
                        window.location.href = "<?= base_url('penjualan/input'); ?>"
                    } else {
                        window.location.href = "<?= base_url('penjualan/input'); ?>"
                    }
                </script>
            <?php
            } else {
            ?>
                <script>
                    alert('Gagal Menyimpan Data Pembayaran!')
                    window.location.href = "<?= base_url('penjualan/input'); ?>"
                </script>
<?php
            }
        }
    }

    public function print($faktur_penjualan)
    {
        $data = [
            'title' => 'Print Struk Penjualan',
            'konventer' => $this->konverter,
            'penjualanDetail' => $this->penjualandetailModel->ambilDataDetail($faktur_penjualan)
        ];
        return view('penjualan/print', $data);
    }

    // ================================================================================================== //

    public function dataPenjualan()
    {
        $data = [
            'title' => 'Data Penjualan',
            'penjualan' => $this->penjualanModel->getPenjualan(),
            'penjualanHarian' => $this->penjualanModel->getJumlahOmsetHarian(),
            'penjualanDetail' => $this->penjualandetailModel->getPenjualanDetail(),
            'penjualanBarang' => $this->penjualandetailModel->getPenjualanBarang(),
            'rowHargaJualMax' => $this->penjualandetailModel->setMaxHargaJualDetail(),
            'jmlBarangTerjual' => $this->penjualandetailModel->setJmlBarangTerjual(),
            'rowMaxJml' => $this->penjualandetailModel->setMaxJmlBarang(),

        ];
        return view('penjualan/data_penjualan', $data);
    }
}
