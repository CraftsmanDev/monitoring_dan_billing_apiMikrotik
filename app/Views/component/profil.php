<div class="view-action" id="profile">
    <form 
    id="form-profil"
    action="<?= base_url('dashboard/profil/put') ?>"
    class="wrapper-profile">
    <?= csrf_field() ?>
        <input type="hidden" name="id">
        <div class="image-profil">
            <img src="<?= base_url('assest/'. session()->get('foto') ?: 'admin2.jpg')?>" alt="">
            <div>
                <input type="file" id="upload" data-file="upload" name="foto" hidden>
                <input type="text" placeholder="Foto profil" data-file-name="upload" readonly>
                <button 
                    type="button"
                    onclick="document.getElementById('upload').showPicker()" 
                    class="btn-upload">
                    Upload Foto
                </button>
            </div>
        </div>
        <div style="flex:1;">
            <div class="form-input">

                <div>
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username">
                </div>

                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password">
                </div>

                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi password">
                </div>

            </div>
        </div>
    </form>
     <div class="action-profile">
        <button type="button" class="btn-cancel">
                    Batal
                </button>
                <button form="form-profil" type="submit" class="btn-save">
                    Simpan Perubahan
                </button>
            </div>
</div>