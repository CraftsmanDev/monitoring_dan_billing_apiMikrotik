<div class="view-action" form-menu="edit">
    <div class="form-container">
        <span><i class="fa-solid fa-user-gear"></i> Edit Petugas</span>
        <form
        id="form-edit"
        form-submit
        action="<?= base_url('dashboard/petugas/put') ?>"
        class="form-action"
        form-action="edit"
        method="post">
            <input type="hidden" name="id">
            <?= csrf_field() ?>
            <div class="form-group-1">
                <label>Username</label>
                <input type="text" name="username">
            </div>
            <div class="form-group-1">
                <label>Nama Lengkap</label>
                <input type="text" name="nama">
            </div>
            <div class="form-group-1">
                <label>Nomor WA</label>
                <input type="text" name="nomor_wa">
            </div>
            <div class="form-group-1">
                <label>Role</label>
                <select name="role">
                    <option value="admin">Admin</option>
                    <option value="service">Service</option>
                    <option value="billing">Billing</option>
                </select>
            </div>
            <div class="form-group-1">
                <label>Password</label>
                <input type="password" name="password">
            </div>
        </form>
        <div class="btn-form">
            <button id="btn-cancel" style="--action-color:#FF6E4D; --action-color-hover:#FA6746;"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
            <button form="form-edit" type="submit" style="--action-color:#69FA9F; --action-color-hover:#60F79B;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
        </div>
    </div>
</div>