<div class="view-action" form-menu="import">
    <div class="form-container">
        <span><i class="fa-solid fa-user-plus"></i> Tambah Pelanggan</span>
        <form
        id="form-import"
        form-submit
        action="<?= base_url('dashboard/pelanggan/import_excel') ?>"
        method="post"
        form-action="import"
        class="form-action"
        enctype="multipart/form-data"
        >
        <?= csrf_field() ?>
            <div class="form-group-1">
                <label>Import Excel</label>
                <div>
                    <input type="file" class="input-excel" id="excel" data-file="file_excel" name="file_excel" accept=".xls,.xlsx" hidden>
                    <input type="text" id="excel-file" data-file-name="file_excel">
                    <span class="input-icon" onclick="document.getElementById('excel').showPicker()">
                        <i class="fa-solid fa-upload"></i>
                    </span>
                </div>
            </div >
            <div class="form-group-1">
                <select name="mode_import" id="">
                    <option value="">--Pilih mode simpan--</option>
                    <option value="database">Only Database</option>
                    <option value="both">Database dan Mikrotik</option>
                </select>
            </div>
            <div class="panduan-import">
                <h4>
                    <i class="fa-solid fa-circle-info"></i>
                    Panduan Import Excel
                </h4>
                <div class="panduan-item">
                    <i>1.</i>
                    <p>
                        Upload file dengan format <b>.xls</b> atau <b>.xlsx</b>.
                    </p>
                </div>
                <div class="panduan-item">
                    <i>2.</i>
                    <p>
                        Pastikan baris pertama adalah header tabel.
                    </p>
                </div>
                <div class="panduan-item">
                    <i>3.</i>
                    <p>
                        Urutan kolom Excel harus:
                        <br>
                        <b>
                            ID Pelanggan | Nama | Nomor WA | Area | Nama Paket | Tanggal Register
                        </b>
                    </p>
                </div>
                <div class="panduan-item">
                    <i>4.</i>
                    <p>
                        Nama paket harus sama dengan data paket yang tersedia di sistem.
                    </p>
                </div>
                <div class="panduan-item">
                    <i>5.</i>
                    <p>
                        Format tanggal register menggunakan:
                        <b>YYYY-MM-DD</b>
                        <br>
                        Contoh:
                        <b>2026-05-11</b>
                    </p>
                </div>
                <div class="panduan-item">
                    <i>6.</i>
                    <p>
                        Mode <b>Only Database</b> hanya menyimpan data pelanggan.
                    </p>
                </div>
                <div class="panduan-item">
                    <i>7.</i>
                    <p>
                        Mode <b>Database dan Mikrotik</b> akan menyimpan data pelanggan sekaligus membuat PPP Secret di Mikrotik.
                    </p>
                </div>
                <div class="panduan-item">
                    <i>8.</i>
                    <p>
                        Pastikan ID pelanggan tidak duplikat.
                    </p>
                </div>
            </div>
        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-import" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>