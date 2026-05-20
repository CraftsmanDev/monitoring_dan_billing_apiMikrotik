<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<section class="wrapper body">
    <div class="table-header">
        <div class="filter-wrapper">
            <select class="filter-data">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class="action">
            <div class="filter">
                <select class="bulan-data">
                    <option value="">Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                <select class="tahun-data">
                    <option value="">Tahun</option>
                    <?php for ($i = date('Y'); $i >= 2020; $i--) : ?>
                        <option value="<?= $i ?>">
                            <?= $i ?>
                        </option>

                    <?php endfor ?>
                </select>
                <button type="button" style="--action-color:#0e75fb;">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>
            </div>
            <button action="export" data-url="<?= base_url('dashboard/laporan/perbaikan/export') ?>" style="--action-color:#08A185;">
                <i class="fa-solid fa-file-export"></i>
                <span class="text-full">Export Laporan</span>
                <span class="text-short">Export</span>
            </button>
        </div>
    </div>
    <div class="table-wrapper">
        <table class="table" data-url="laporan/perbaikan/fetch">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pelanggan</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Anggaran</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody class="table-body">
            </tbody>
        </table>
        <div class="table-footer">
            <div class="showing-data"></div>
            <div class="custom-pagination"></div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>