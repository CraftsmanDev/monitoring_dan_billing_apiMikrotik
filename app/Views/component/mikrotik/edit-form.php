<div class="view-action" form-menu="edit">
    <div class="form-container">
        <span><i class="fa-solid fa-box"></i> Tambah Paket</span>
        <form id="form-edit"
            form-submit
            action="<?= base_url('dashboard/mikrotik/put') ?>"
            method="post"
            class="form-action"
            form-action="edit">

            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

            <input type="hidden" name="id">

            <div class="form-group-1">
                <label>Address</label>
                <input type="text" name="address" required>
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
                <input type="text" name="port" required>
            </div>

        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-edit" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>