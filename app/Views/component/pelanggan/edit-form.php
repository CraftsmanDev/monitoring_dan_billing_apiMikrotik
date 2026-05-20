<div class="view-action" form-menu="edit">
    <div class="form-container">
        <span><i class="fa-solid fa-user-plus"></i> Tambah Pelanggan</span>
        <form id="form-edit"
        form-submit
        action="<?= base_url('dashboard/pelanggan/put') ?>"
        method="post"
        form-action="edit"
        class="form-action">
         <?= csrf_field() ?>
            <div class="form-group-1">
                <label>ID Pelanggan</label>
                <input type="text" name="id_pelanggan">
            </div>
            <div class="form-group-1">
                <label>Nama</label>
                <input type="text" name="nama">
            </div>
            <div class="form-group-1">
                <label>Nomor Telpon</label>
                <div>
                    <input type="text" name="nomor_wa">
                    <span class="input-icon">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                </div>
            </div>
            <div class="form-group-1">
                <label>Area</label>
                <div>
                    <input type="text" name="area">
                    <span  class="input-icon">
                        <i class="fa-solid fa-crosshairs"></i>
                    </span>
                </div>
            </div>
            <div class="form-group-1">
                <label>Paket</label>
                <select name="paket_id">
                    <option value="">-- Pilih Paket --</option>
                    <?php foreach($paket as $p): ?>
                        <option value="<?= $p['id_paket'] ?>">
                            <?= $p['nama_paket'] ?>
                            @ Rp. <?= number_format($p['tarif'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group-1">
                <label>Tanggal Register</label>
                <div>
                    <input type="date" id="tanggal_register" name="tanggal_register">
                    <span  class="input-icon" onclick="document.getElementById('tanggal_register').showPicker()">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>
                </div>
            </div>
        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-edit" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>