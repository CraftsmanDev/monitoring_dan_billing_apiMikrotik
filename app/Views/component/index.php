<?= $this->extend('dashboard') ?>

<?= $this->section('content') ?>
<section class="wrapper" data-page="dashboard">
    <div class="card">
        <div class="card-icon" style="--card-color:#0088FF;">
            <i class="fa-solid fa-users"></i>
        </div>
        <span>
            <h1>PELANGGAN</h1>
            <p id="total_pelanggan">0</p>
        </span>
    </div>
    <div class="card">
        <div class="card-icon" style="--card-color:#CC4422">
            <i class="fa-solid fa-gear"></i>
        </div>
        <span>
            <h1>CPU TRAFIK</h1>
            <p id="cpu_load">0%</p>
        </span>
    </div>
    <div class="card">
        <div class="card-icon" style="--card-color:#EFC726">
            <i class="fa-solid fa-money-bill-trend-up"></i>
        </div>
        <span>
            <h1>PEMASUKAN</h1>
            <p id="total_pemasukan">Rp.0</p>
        </span>
    </div>
    <div class="card">
        <div class="card-icon" style="--card-color:#AFC723">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
        <span>
            <h1>Pendapatan</h1>
            <p id="total_pendapatan">Rp.0</p>
        </span>
    </div>
    <div class="card">
        <div class="card-icon" style="--card-color:#22F0F4;">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <span>
            <h1>PENGELUARAN</h1>
            <p id="total_pengeluaran">Rp.0</p>
        </span>
    </div>
</section>

<section class="card-control">
    <section class="card-network" style="--border-color:#4EFF7B;">
        <div class="card-header">
            <div class="header-left">
                <h3>Network Traffic</h3>
                <p>Realtime monitoring traffic interface</p>
            </div>

            <div class="traffic-info">
                <div class="info download">
                    <span></span>
                    <p>Download</p>
                    <h4 id="download-speed">0 Mbps</h4>
                </div>

                <div class="info upload">
                    <span></span>
                    <p>Upload</p>
                    <h4 id="upload-speed">0 Mbps</h4>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="chart-container">
                <canvas id="network"></canvas>
            </div>
        </div>
    </section>
    <div class="card-device" style="--border-color:#48A8FB;">
        <div class="card-header">
            <span>
                <i class="fa-solid fa-mobile"></i>
                <h3>Device aktif</h3>
            </span>
            <span>
                <p>pelanggan aktif:</p>
                <span id="total_active">0</span>
            </span>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>IP Address</th>
                            <th>Uptime</th>
                            <th>Download / Upload</th>
                            <th>Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="active_table">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>