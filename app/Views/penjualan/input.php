<?php

echo $this->extend('layout/template');
echo $this->section('page-content');

foreach ($penjualan as $penjualan) {
}

?>


<div class="container mt-3">
    <div class="row">
        <div class="col">
            <h1><i class="fas fa-keyboard"></i><u> Input Kasir / Penjualan</u></h1>
            <div class="row mt-5">
                <div class="col-md-3 mb-3">
                    <label>Faktur</label>
                    <input type="text" name="faktur_penjualan" id="faktur_penjualan" class="form-control form-control-sm" value="<?= $konverter->fakturJual(); ?>" readonly>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Tanggal</label>
                    <input type="text" name="tanggal_penjualan" id="tanggal_penjualan" class="form-control form-control-sm" value="<?= date('d-M-Y'); ?>" readonly>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Konsumen</label>
                    <div class="input-group">
                        <input type="text" name="nama_konsumen" id="nama_konsumen" class="form-control form-control-sm" value="-" readonly>
                        <input type="hidden" name="kode_konsumen_penjualan" id="kode_konsumen_penjualan" value="CUST-000">
                        <button class="btn btn-outline-info btn-sm" type="button" id="button-addon2"><i class="fa fa-search"></i> </button>

                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Action</label><br>
                    <!-- <a href="<?= base_url('penjualan/simpanTransaksi'); ?>" class="btn btn-success btn-sm btn-circle" id="buttonSimpanTransaksi" data-bs-toggle="tooltip" data-bs-placement="top" title="Simpan Transaksi"><i class="fa fa-save"></i></a> -->
                    <button type="button" class="btn btn-success btn-sm btn-circle" id="buttonSimpanTransaksi" data-bs-toggle="tooltip" data-bs-placement="top" title="Simpan Transaksi">
                        <i class="fa fa-save"></i>
                    </button>
                    <form action="<?= base_url('penjualan/hapusTransaksi'); ?>" method="POST" class="d-inline">
                        <input type="hidden" name="_method" value="DELETE">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn btn-danger btn-sm btn-circle" id="buttonHapusTransaksi" onclick="return confirm('Anda Yakin Akan Membatalkan Transaksi Ini?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Transaksi">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="barcode">Barcode</label>
                <input type="text" class="form-control form-control-sm" name="barcode" id="barcode" autofocus>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Nama Barang</label>
                <input type="text" class="form-control form-control-sm" name="nama_barang" id="nama_barang" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" class="form-control form-control-sm" name="jumlah" id="jumlah" value="1">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Total Bayar</label>
                <input type="text" class="form-control" name="total_bayar" id="total_bayar" style="text-align: right; color:blue; font-weight : bold; font-size:24pt;" value="0" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 dataDetailPenjualan">

        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;">

</div>
<div class="viewmodalPembayaran" style="display: none;">

</div>

<script>
    $(document).ready(function() {
        dataDetailPenjualan()
        hitungTotalBayar()

        $('#barcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault()
                cekBarcode()
            }
        })
        $('#buttonSimpanTransaksi').click(function(e) {
            e.preventDefault();
            pembayaran()
        });
        $('#jumlah').keydown(function(e) {
            if (e.keyCode == 27) {
                e.preventDefault()
                $('#barcode').focus()
            }
        })
        $(this).keydown(function(e) {
            if (e.keyCode == 27) {
                e.preventDefault()
                $('#barcode').focus()
            }
            if (e.keyCode == 115) {
                e.preventDefault()
                $('#buttonHapusTransaksi').click()
            }
            if (e.keyCode == 119) {
                e.preventDefault()
                $('#buttonSimpanTransaksi').click()
            }
        })
    });

    function pembayaran() {
        let fakturPenjualan = $('#faktur_penjualan').val()

        $.ajax({
            type: "post",
            url: "<?= base_url('penjualan/simpanTransaksi'); ?>",
            data: {
                fakturPenjualan: fakturPenjualan,
                tanggalPenjualan: $('#tanggal_penjualan').val(),
                kodeKonsumenPenjualan: $('#kode_konsumen_penjualan').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalPembayaran').html(response.data).show()
                    $('#pembayaran').on('shown.bs.modal', function(e) {
                        $('#jumlah_uang').focus()
                    })
                    $('#pembayaran').modal('show')
                }
                if (response.error) {
                    alert(response.error)
                }
            }
        });

    }

    function dataDetailPenjualan() {
        $.ajax({
            type: "post",
            url: "<?= base_url('/penjualan/detail'); ?>",
            data: {
                fakturPenjualan: $('#faktur_penjualan').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.dataDetailPenjualan').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('.dataDetailPenjualan').html(response.data)
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        })
    }

    function cekBarcode() {
        let barcode = $('#barcode').val()
        if (barcode == 0) {
            $.ajax({
                url: "<?= base_url('penjualan/dataBarang'); ?>",
                dataType: "json",
                success: function(response) {
                    $('.viewmodal').html(response.view_modal).show()
                    $('#cari-barang').modal('show')
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                }
            });
        } else {
            $.ajax({
                type: "post",
                url: "<?= base_url('penjualan/saveTemp'); ?>",
                data: {
                    barcode: barcode,
                    namaBarang: $('#nama_barang').val(),
                    jumlah: $('#jumlah').val(),
                    fakturPenjualan: $('#faktur_penjualan').val(),
                },
                dataType: "json",
                success: function(response) {
                    if (response.cekBarang == 'banyak') {
                        $.ajax({
                            type: "post",
                            url: "<?= base_url('penjualan/dataBarang'); ?>",
                            data: {
                                keyword: barcode
                            },
                            dataType: "json",
                            success: function(response) {
                                $('.viewmodal').html(response.view_modal).show()
                                $('#cari-barang').modal('show')
                            },
                            error: function(xhr, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                            }
                        });
                    }
                    if (response.sukses == 'berhasil') {
                        dataDetailPenjualan();
                        clearInput();
                    }
                    if (response.error) {
                        alert(response.error)
                        dataDetailPenjualan();
                        clearInput();
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
                }
            });
        }
    }

    function clearInput() {
        $('#barcode').val('')
        $('#nama_barang').val('')
        $('#jumlah').val('1')
        $('#barcode').focus()

        hitungTotalBayar()
    }

    function hitungTotalBayar() {
        $.ajax({
            type: "post",
            url: "<?= base_url('penjualan/hitungTotalBayar'); ?>",
            data: {
                fakturPenjualan: $('#faktur_penjualan').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.total_bayar) {
                    $('#total_bayar').val(response.total_bayar)
                }
            },
            error: function(xhr, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError)
            }
        });
    }
</script>

<?= $this->endSection(); ?>