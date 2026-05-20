<div class="view-action" form-menu="edit">
     <div class="form-container">
        <span><i class="fa-solid fa-box"></i> Tambah Paket</span>
         <form 
            id="form-edit"
            form-submit
            action="<?= base_url('dashboard/perbaikan/put') ?>"
            method="post"
            class="form-action"
            form-action="edit">
            <?= csrf_field() ?>
            <input type="hidden" name="id">
            <div class="form-group-1">
                <label>ID Pelanggan</label>
                <input type="text" name="id_pelanggan" id="id_pelanggan">
            </div>
            <div class="form-group-1">
                <label>Nama</label>
                <input type="text" name="nama" id="nama" disabled>
            </div>
            <div class="form-group-2">
                <div class="form-group-1">
                    <label>Kategori</label>
                    <input type="text" name="kategori">
                </div>
                <div class="form-group-1">
                    <label for="">Status</label>
                    <select name="status" id="">
                        <option value="pending">Pending</option>
                        <option value="proses">proses</option>
                        <option value="selesai">selesai</option>
                        <option value="hold">hold</option>
                    </select>
                </div>
            </div>
            <div class="form-group-1">
                <label>Anggaran</label>
                <div>
                    <input type="text" name="anggaran">
                    <span class="input-icon">
                        <i>Rp.</i>
                    </span>
                </div>
            </div>
            <div class="form-group-1">
                <label>Keterangan</label>
                <textarea name="keterangan" id=""></textarea>
            </div>
            <div class="form-group-1">
                <label>tanggal</label>
                <div>
                    <input type="date" id="edit-tanggal" name="tanggal">
                    <span class="input-icon" onclick="document.getElementById('edit-tanggal').showPicker()">
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