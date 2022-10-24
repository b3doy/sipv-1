<ul class="navbar-nav bg-gradient-light sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SIP<sub>v</sub>-1.0 </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/'); ?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Home</span></a>
    </li>

    <!-- sidebar Heading -->

    <!-- Nav Item - User's Management -->
    <?php if (in_groups('Superuser')) : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('user'); ?>">
                <i class="fas fa-fw fa-user"></i>
                <span>Users</span></a>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <?php if (in_groups('Superuser') || in_groups('Administrator') || in_groups('Admin-Pembelian')) : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('supplier'); ?>">
                <i class="fas fa-people-carry"></i>
                <span>Supplier</span>
            </a>
        </li>
    <?php endif; ?>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('konsumen'); ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Konsumen</span>
        </a>

    </li>

    <?php if (in_groups('Superuser') || in_groups('Administrator') || in_groups('Admin-Gudang')) : ?>
        <!-- Nav Item - Inventory Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#inventory" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-pallet"></i>
                <span>Inventory</span>
            </a>
            <div id="inventory" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-transparent py-2 collapse-inner rounded">
                    <a class="collapse-item" href="<?= base_url('kategori'); ?>"> Data Kategori</a>
                    <a class="collapse-item" href="<?= base_url('satuan'); ?>">Data Satuan</a>
                    <a class="collapse-item" href="<?= base_url('inventory'); ?>">Data Inventory</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Transaksi Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#penjualan" aria-expanded="true" aria-controls="penjualan">
            <i class="fas fa-money-bill-wave"></i>
            <span>Transaksi</span>
        </a>
        <div id="penjualan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-transparent py-2 collapse-inner rounded">
                <?php if (in_groups('Superuser') || in_groups('Administrator') || in_groups('Kasir')) : ?>
                    <a class="collapse-item" href="<?= base_url('penjualan'); ?>"> Penjualan</a>
                <?php endif; ?>
                <?php if (in_groups('Superuser') || in_groups('Administrator') || in_groups('Admin-Pembelian')) : ?>
                    <!-- <a class="collapse-item" href="<?= base_url('pembelian'); ?>"> Pembelian</a> -->
                <?php endif; ?>
            </div>
        </div>
    </li>



    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 bg-gray-600" id="sidebarToggle"></button>
    </div>

</ul>