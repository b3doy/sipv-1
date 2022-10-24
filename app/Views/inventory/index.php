<?php

echo $this->extend('layout/template');
echo $this->section('page-content');


?>

<div class="container mt-3">
    <div class="row">
        <div class="col">
            <h1>Inventory List</h1>
            <!-- Button trigger modal -->
            <?php
            if ($kat == null || $sat == null) {
                echo '<a href="' . base_url('/inventory/batal') . '" class="btn btn-primary my-4"><i class=" fa fa-plus-circle"></i> Tambah Data</a>';
            } else {
                echo '<button type="button" class="btn btn-primary my-4" data-bs-toggle="modal" data-bs-target="#tambah-Inventory"><i class=" fa fa-plus-circle"></i> Tambah Data</button>';
            }
            ?>

            <table class="table table-hover" id="inventoryTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PLU</th>
                        <th>Nama Barang</th>
                        <th>Barcode</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Modal - Tambah Inventory -->
<div class="modal fade" id="tambah-Inventory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('inventory/save'); ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori" id="kategori" class="form-control" required>
                            <option value="">--- Pilih Kategori ---</option>
                            <?php foreach ($kategori as $kategori) : ?>
                                <option value="<?= $kategori['id']; ?>" data-kategori="<?= $kategori['nama_kategori']; ?>"><?= $kategori['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>PLU</label>
                        <input type="text" class="form-control" id="plu" name="plu" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                    </div>
                    <div class="mb-3">
                        <label>Barcode</label>
                        <input type="text" class="form-control" id="barcode" name="barcode" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga Beli</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control angka4" id="harga_beli" name="harga_beli" onkeyup="marginJual()" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control angka4" id="harga_jual" name="harga_jual" onkeyup="marginJual()" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Margin</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="margin" name="margin" readonly>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Satuan Barang</label>
                        <div class="input-group mb-3">
                            <select name="satuan" id="satuan" class="form-control">
                                <option value="">----- Pilih Satuan Barang -----</option>
                                <?php foreach ($satuan as $satuan) : ?>
                                    <option value="<?= $satuan['id']; ?>"><?= $satuan['nama_satuan']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Nama Supplier</label>
                        <div class="input-group">
                            <select name="kode_supplier" id="kode_supplier" class="form-control">
                                <option value="">----- Pilih Supplier -----</option>
                                <?php foreach ($supplier as $supplier) : ?>
                                    <option value="<?= $supplier['kode_supplier']; ?>"><?= $supplier['nama_supplier']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Tambahkan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $('#kategori').on('change', function() {
        let kategori = $('#kategori option:selected').data('kategori')
        let plu = kategori[0] + kategori[1] + kategori[2] + '<?= $konverter->plu() ?>'
        $('#plu').val(plu)
    })

    function marginJual() {
        let hargaBeli = document.getElementById('harga_beli').value
        hargaBeli01 = hargaBeli.split('.').join('')

        let hargaJual = document.getElementById('harga_jual').value
        hargaJual01 = hargaJual.split('.').join('')

        let margin = 100 - (parseFloat((hargaBeli01) / (parseInt(hargaJual01)) * 100))
        document.getElementById('margin').value = margin.toFixed(2)
    }

    table = $('#inventoryTable').DataTable({
        "order": [],
        "processing": true,
        "serverside": true,
        "ajax": {
            "url": "<?= base_url('/inventory/inventoryTable'); ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0],

        }, ]
    });
</script>

<?= $this->endSection(); ?>