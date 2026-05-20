<ul class="profil" data-menu="profil">
    <li class="content-profil">
        <div class="nav_avatar">
            <img src="<?= base_url('assest/' . session()->get('foto') ?: 'admin2.jpg') ?>" alt="foto">
        </div>
        <span class="nav_username"><?= session()->get('nama') ?> - <?= session()->get('role') ?></span>
    </li>
    <div class="btn-profil">
        <span 
        data-action="edit-profil" 
        data-id="<?= session()->get('id_users')?>" 
        data-nama="<?= session()->get('nama')?>"
        data-username="<?= session()->get('username')?>"
        >
            profil
        </span>
        <a href="<?= base_url('logout') ?>">logout</a>
    </div>
</ul>