<div class="view-action" form-menu="tambah">
    <div class="form-container">
        <span><i class="fa-solid fa-box"></i> Tambah Paket</span>
        <form 
        id="form-add"
        form-submit
            action="<?= base_url('dashboard/paket_layanan/add') ?>"
            method="post"
            class="form-action"
            form-action="tambah">
             <?= csrf_field() ?>
            <div class="form-group-1">
                <label>Nama Paket</label>
                <input type="text" name="nama_paket" required>
            </div>
            <div class="form-group-1">
                <label>Kecepatan</label>
                <select name="kecepatan" id="">
                    <option value="">select Kecepatan</option>
                    <option value="128K/128K">ISOLIR</option>
                    <option value="10M/10M">10M/10M</option>
                    <option value="20M/20M">20M/20M</option>
                    <option value="25M/25M">25M/25M</option>
                    <option value="30M/30M">30M/30M</option>
                    <option value="50M/50M">50M/50M</option>
                </select>
            </div>
            <div class="form-group-1">
                <label>Tarif</label>
                <input type="text" id="tarif_view">
                <input type="hidden" name="tarif" id="tarif_real">
            </div>
        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-add" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>