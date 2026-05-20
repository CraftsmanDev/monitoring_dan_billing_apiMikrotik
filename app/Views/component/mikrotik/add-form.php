<div class="view-action" form-menu="tambah">
    <div class="form-container">
        <span><i class="fa-solid fa-box"></i> Tambah Paket</span>
        <form 
        id="form-add"
        form-submit
            action="<?= base_url('dashboard/mikrotik/add') ?>"
            method="post"
            class="form-action"
            form-action="tambah">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="form-group-1">
                <label>Address</label>
                <input type="text" name="address"  placeholder="Contoh: id1.vpn-remote.com" required>
            </div>
            <div class="form-group-1">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group-1">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group-1">
                <label>Port</label>
                <input type="text" placeholder="Contoh: 8728" name="port" required>
            </div>
        </form>
        <div class="test">
            <button type="button" id="btn-test">
                Test Connection
            </button>
            <div class="result" id="result-test"></div>
        </div>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-add" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>