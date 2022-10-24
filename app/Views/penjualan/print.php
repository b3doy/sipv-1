<link rel="stylesheet" href="<?= base_url(); ?>/public/assets/css/sb-admin-2.min.css">
<style>
    @media print {
        @page {
            margin-left: 80px;
            margin-right: -140px;
            font-size: 10px;
        }

        .noprint {
            display: none;
        }
    }

    .font-table {
        font-size: 32px;
    }

    #printPage {
        width: 100%;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card" id="printPage">
                <h1>Struk Pembayaran</h1>
                <h3>sipV-1.0</h3>
                <?php foreach ($penjualanDetail as $penjualan) {
                } ?>
                <h3>No Faktur : <?= $penjualan['faktur_penjualan']; ?></h3>
                <hr>
                <table class="table-sm font-table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                            <th>Harga</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php foreach ($penjualanDetail as $penjualan) : ?>
                            <tr>
                                <td><?= $penjualan['nama_barang']; ?></td>
                                <td><?= $penjualan['jumlah_penjualan_detail']; ?></td>
                                <td class="text-right"><?= $konventer->rupiah($penjualan['harga_jual_penjualan_detail']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <table class="table-sm font-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="width: 30%;"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sub Total</td>
                            <td>:</td>
                            <td class="text-right"><?= $konventer->rupiah($penjualan['total_kotor_penjualan']); ?></td>
                        </tr>
                        <tr>
                            <td>Diskon</td>
                            <td>:</td>
                            <td class="text-right"><?= $penjualan['diskon_nominal_penjualan']; ?></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>:</td>
                            <td class="text-right"><?= $konventer->rupiah($penjualan['total_bersih_penjualan']); ?></td>
                        </tr>
                        <tr>
                            <td>Bayar</td>
                            <td>:</td>
                            <td class="text-right"><?= $konventer->rupiah($penjualan['jumlah_uang']); ?></td>
                        </tr>
                        <tr>
                            <td>Kembalian</td>
                            <td>:</td>
                            <td class="text-right"><?= $konventer->rupiah($penjualan['sisa_uang']); ?></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <p>Terima Kasih Telah Berbelanja di Tempat Kami.</p>
                <button type="submit" class="btn btn-success btnprint noprint">Print</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>/public/assets/js/jquery-3.0.6.js"></script>
<script>
    $(document).ready(function() {
        $('.btnprint').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault()
                window.print()
                window.close()
            }
        })
    });
</script>