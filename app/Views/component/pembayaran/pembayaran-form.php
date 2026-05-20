<div class="view-action" form-menu="pembayaran">
    <div class="form-container">
        <span><i class="fa-solid fa-money-bill"></i> Pembayaran</span>
        <form
        form-submit
        id="pembayaran"
        class="form-action" 
        action="<?= base_url('dashboard/pembayaran/put') ?>"
        method="post"
        enctype="multipart/form-data"
        form-action="tambah">
         <?= csrf_field() ?>
         <input type="text" name="id" hidden>
            <div class="form-group-2">
                <div class="form-group-1">
                    <label>Invoice</label>
                    <input type="text" name="invoice" disabled style="background:#f2f2f2; cursor:not-allowed;">
                </div>
                <div class="form-group-1">
                    <label>Nama</label>
                    <input type="text" name="nama" disabled>
                </div>
            </div>
            <div class="form-group-2">
                <div class="form-group-1">
                    <label>Paket</label>
                    <input type="text" name="nama_paket" disabled style="background:#f2f2f2; cursor:not-allowed;">
                </div>
                <div class="form-group-1">
                    <label>Total Tagihan</label>
                    <div>
                        <input type="text" name="total_tagihan" readonly style="background:#f2f2f2; cursor:not-allowed;">
                        <span class="input-icon">
                            <i>Rp.</i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group-2">
                <div class="form-group-1">
                    <label>Periode</label>
                    <input type="text" name="periode" disabled style="background:#f2f2f2; cursor:not-allowed;">
                </div>
                <div class="form-group-1">
                    <label>Via Pembayaran</label>
                    <select id="optional-select" name="metode">
                        <option value="">-- Pilih Via Pembayaran --</option>
                        <option value="transfer">transfer</option>
                        <option value="cash">cash</option>
                    </select>
                </div>
            </div>
            <div class="optional-group" data-optional="cash">
                <div class="form-group-2">
                    <div class="form-group-1">
                    <label>tanggal pembayaran</label>
                    <div>
                        <input type="datetime-local" id="tanggal" name="tanggal_bayar">
                        <span class="input-icon" onclick="document.getElementById('tanggal').showPicker()">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group-1">
                    <label>Nominal Uang</label>
                    <div>
                        <input type="text" name="total_bayar">
                        <span class="input-icon">
                            <i>Rp.</i>
                        </span>
                    </div>
                </div>
                </div>
            </div>
            <div class="optional-group" data-optional="transfer">
                <div class="form-group-2">
                    <div class="form-group-1">
                        <label>tanggal pembayaran</label>
                        <div>
                            <input type="datetime-local" id="tanggal-bayar" name="tanggal_bayar">
                            <span class="input-icon" onclick="document.getElementById('tanggal-bayar').showPicker()">
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                        </div>
                    </div>
                    <div class="form-group-1">
                        <label>Nominal Uang</label>
                        <div>
                            <input type="text" name="total_bayar">
                            <span class="input-icon">
                                <i>Rp.</i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group-1">
                    <label>Import Bukti Pembayaran</label>
                    <div>
                        <input type="file" id="bukti" data-file="bukti" name="bukti" hidden>
                        <input type="text" id="bukti-file" data-file-name="bukti" class="file-name" placeholder="Pilih file..." readonly>
                        <span class="input-icon" onclick="document.getElementById('bukti').showPicker()">
                            <i class="fa-solid fa-upload"></i>
                        </span>
                    </div>
                </div>
            </div>
        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="pembayaran" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>