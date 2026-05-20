<nav class="flex col">
    <div class="container-nav between">
        <div class="left-nav">
            <i class="nav_toggle fa-solid fa-bars" id="toggle"></i>
            <div class="nav-info">
                <span class="date"></span>
                <span class="time"></span>
            </div>
        </div>
        <div class="right-nav">
            <span class="notifikasi notification" data-dropdown="notif">
                <i class="fa-regular fa-bell"></i>
                <div>
                    <p id="total-notification">0</p>
                </div>
            </span>
            <span data-dropdown="profil">
                <div class="nav_avatar">
                    <img src="<?= base_url('assest/' . session()->get('foto') ?: 'admin2.jpg') ?>" alt="foto">
                </div>
                <span class="nav_username"><?= session()->get('nama') ?></span>
            </span>
        </div>
        <?= $this->include('component/profil_active.php') ?>
        <?= $this->include('component/notifikasi.php') ?>
        <?= $this->include('component/profil.php') ?>
    </div>
    <div>
    </div>
</nav>