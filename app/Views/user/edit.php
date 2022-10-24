<?php

echo $this->extend('layout/template');
echo $this->section('page-content')

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-10">
                    <h2>Edit User's Role</h2>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('user/index'); ?>" class="btn btn-secondary btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Kembali Ke Menu Sebelumnya"><i class="fa fa-undo"></i> </a>
                </div>
            </div>
            <form action="<?= base_url('user/update/' . $users->userid); ?>" method="POST">
                <?= csrf_field(); ?>
                <input type="text" name="userid" value="<?= $users->userid; ?>">
                <div class="mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" value="<?= $users->fullname; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="name">Role</label>
                    <select name="groupid" id="groupid" class="form-control" onchange="desc()">
                        <option value="<?= $users->groupid; ?>" selected><?= $users->groupid . ' - ' . $users->name; ?></option>
                        <option value="">----- Pilih User's Role -----</option>
                        <?php foreach ($role as $role) : ?>
                            <option value="<?= $role->id; ?>"><?= $role->id . ' - ' . $role->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control" readonly>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> Update</button>
            </form>
        </div>
    </div>
</div>

<script>
    function desc() {
        let groupid = document.getElementById('groupid').value
        if (groupid == "1") {
            document.getElementById('description').value = "Owner"
        } else if (groupid == "2") {
            document.getElementById('description').value = "Site-Administrator"
        } else if (groupid == "3") {
            document.getElementById('description').value = "Regular-User"
        } else if (groupid == "4") {
            document.getElementById('description').value = "Kasir"
        } else if (groupid == "5") {
            document.getElementById('description').value = "Admin-Inventory"
        } else if (groupid == "6") {
            document.getElementById('description').value = "Admin-Pembelian"
        }
    }
</script>

<?= $this->endSection(); ?>