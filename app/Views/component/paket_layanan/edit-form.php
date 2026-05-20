<div class="view-action" form-menu="edit">
    <div class="form-container">
        <span><i class="fa-solid fa-box"></i> Edit Paket</span>
        <form
            form-submit 
            id="form-edit"
            action="<?= base_url('dashboard/paket_layanan/put') ?>"
            class="form-action"
            form-action="edit"
            method="post">
            <input type="hidden" name="id">
            <?= csrf_field() ?>
            <div class="form-group-1">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket">
            </div>
            <div class="form-group-1">
                <label>Kecepatan</label>
                <input type="text" name="kecepatan">
            </div>
            <div class="form-group-1">
                <label>Tarif</label>
                <input type="text" name="tarif">
            </div>
        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-edit" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>