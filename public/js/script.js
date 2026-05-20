document.addEventListener('DOMContentLoaded', () => {
    initTogglePassword();
    initSidebar();
    initDropdown();
    initOptionalSelect();
    initDateTime();
    initTrafficChart();
    initBtnAction();
    initFileUpload();
    rupiah();
    loadData();
    initDashboard();
    initProfile();
    initNotification();
});

function initTogglePassword() {
    window.togglePassword = function () {
        const password = document.getElementById("password");
        const icon = document.getElementById('eyeIcon');

        if (!password || !icon) return;

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            password.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
}

function initSidebar() {
    const toggleBtn = document.getElementById('toggle');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-active');
        });
    }
}

function initDropdown() {
    const buttons = document.querySelectorAll('[data-dropdown]');
    const menus = document.querySelectorAll('[data-menu]');

    if (buttons.length === 0) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-dropdown');

            menus.forEach(menu => {
                if (menu.getAttribute('data-menu') === target) {
                    menu.classList.toggle('show');
                } else {
                    menu.classList.remove('show');
                }
            });
        });
    });
}

function initOptionalSelect() {
    const select = document.getElementById('optional-select');
    const groups = document.querySelectorAll('.optional-group');
    if (!select) return;
    function updateGroups(value) {
        groups.forEach(group => {
            const key = group.dataset.optional;
            const isActive = value === key;
            if (isActive) {
                group.classList.add('show');
            } else {
                group.classList.remove('show');
            }
            group.querySelectorAll('input, select, textarea')
                .forEach(el => {
                    if (el.type === 'file') return;
                    el.disabled = !isActive;
                    if (!isActive) {
                        el.value = '';
                    }
                });
        });
    }
    select.addEventListener('change', function () {
        updateGroups(this.value);
    });
    updateGroups(select.value);
}

function initDateTime() {
    function updateTime() {
        const dateEl = document.querySelector('.date');
        const timeEl = document.querySelector('.time');

        if (!dateEl || !timeEl) return;

        const now = new Date();

        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();

        const date = `${day}/${month}/${year}`;

        const hour = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const time = `${hour}:${minutes}:${seconds}`;

        dateEl.textContent = date;
        timeEl.textContent = time;
    }

    updateTime();
    setInterval(updateTime, 1000);
}

function initDashboard() {
    const page = document
        .querySelector('[data-page]')
        ?.dataset.page;
    if (page !== 'dashboard') {
        return;
    }

    function loadDashboard() {
        $.ajax({
            url: baseUrl + 'dashboard/count',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                $('#total_pelanggan').html(
                    res.data.total_pelanggan
                );
                $('#cpu_load').html(
                    res.data.cpu_load + '%'
                );
                $('#total_pemasukan').text(
                    'Rp ' +
                    Number(
                        res.data.total_pemasukan
                    ).toLocaleString('id-ID')
                );
                $('#total_pengeluaran').text(
                    'Rp ' +
                    Number(
                        res.data.total_pengeluaran
                    ).toLocaleString('id-ID')
                );
                $('#total_pendapatan').text(
                    'Rp ' +
                    Number(
                        res.data.total_pendapatan
                    ).toLocaleString('id-ID')
                );
            },
            error: function (xhr) {
                console.log(
                    xhr.responseText
                );
            }
        });
    }
    function loadActive() {
        $.ajax({
            url: baseUrl + 'dashboard/active',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                let html = '';
                $('#total_active').text(
                    res.total
                );
                if (
                    res.data.length === 0
                ) {
                    html = `
                        <tr>
                            <td colspan="6"
                                style="
                                    text-align:center;
                                ">
                                Tidak ada pelanggan aktif
                            </td>
                        </tr>
                    `;
                } else {
                    res.data.forEach(item => {
                        html += `
                            <tr>
                                <td>
                                    ${item.username}
                                </td>
                                <td>
                                    ${item.ip}
                                </td>
                                <td>
                                    ${item.uptime}
                                </td>
                                <td>
                                    ${item.download}
                                    /
                                    ${item.upload}
                                </td>
                                <td>
                                    ${item.rate}
                                </td>
                                <td>
                                    ${item.status}
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#active_table').html(
                    html
                );
            }
        });
    }
    loadDashboard();
    loadActive();
    setInterval(loadDashboard, 10000);
    setInterval(loadActive, 15000);
}

function initTrafficChart() {
    const page = document
        .querySelector('[data-page]')
        ?.dataset.page;
    if (page !== 'dashboard') {
        return;
    }
    let trafficChart;
    const labels = [];
    const downloadData = [];
    const uploadData = [];
    const canvas = document.getElementById('network');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Download',
                    data: downloadData,
                    borderColor: '#36A2EB',
                    backgroundColor:
                        'rgba(54,162,235,0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 0
                },
                {
                    label: 'Upload',
                    data: uploadData,
                    borderColor: '#4BC0C0',
                    backgroundColor:
                        'rgba(75,192,192,0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#888',
                        maxTicksLimit: 10
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color:
                            'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        color: '#888',
                        callback: function (value) {
                            return value + ' Mbps';
                        }
                    }
                }
            }
        }
    });
    function addTrafficData(download, upload) {
        const now = new Date();
        const time =
            now.getHours().toString().padStart(2, '0')
            + ':' +
            now.getMinutes().toString().padStart(2, '0')
            + ':' +
            now.getSeconds().toString().padStart(2, '0');
        labels.push(time);
        downloadData.push(download);
        uploadData.push(upload);
        if (labels.length > 20) {
            labels.shift();
            downloadData.shift();
            uploadData.shift();
        }
        trafficChart.update();
        $('#download-speed')
            .text(download + ' Mbps');
        $('#upload-speed')
            .text(upload + ' Mbps');
    }
    function loadTraffic() {
        $.ajax({
            url: baseUrl + 'dashboard/traffic',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                addTrafficData(
                    parseFloat(res.download),
                    parseFloat(res.upload)
                );
            },
            error: function () {
                console.log(
                    'Traffic gagal dimuat'
                );
            }
        });
    }
    loadTraffic();
    setInterval(() => {
        loadTraffic();
    }, 3000);
}
function initProfile() {
    $(document).on('click', '[data-action="edit-profil"]', function (e) {
        e.preventDefault();
        const data = $(this).data();
        const modal = $('#profile');
        modal.addClass('show');
        $('#overlay').addClass('show');
        modal.find('[name="id"]').val(data.id || '');
        modal.find('[name="nama"]').val(data.nama || '');
        modal.find('[name="username"]').val(data.username || '');
        modal.find('[name="password"]').val('');
        modal.find('[name="confirm_password"]').val('');
    });
    $(document).on('click', '.btn-cancel, #overlay', function () {
        $('#profile').removeClass('show');
        $('#overlay').removeClass('show');
    });
    $(document).on('submit', '#form-profil', function (e) {
        e.preventDefault();
        let form = $(this);
        let formData = new FormData(this);

        if (!formData.get('id')) {
            alert('ID user tidak ditemukan');
            return;
        }

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',

            beforeSend: function () {
                Swal.fire({
                    title: 'Mengupdate profil...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (res) {
                Swal.close();

                if (res.token) {
                    $(`input[name="${csrfName}"]`).val(res.token);
                }

                Swal.fire({
                    icon: res.status,
                    title: res.message
                });

                if (res.status === 'success') {
                    $('#profile').removeClass('show');
                    $('#overlay').removeClass('show');
                    form[0].reset();
                }
            },
            error: function (xhr) {
                Swal.close();
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal update profil'
                });
            }
        });
    });
}
function initBtnAction() {
    $(document)
        .off('click', '#btn-cancel, #overlay')
        .on('click', '#btn-cancel, #overlay', function () {
            $('[form-menu]').removeClass('show');
            $('#overlay').removeClass('show');
        });

    $(document)
        .off('click', '[action]')
        .on('click', '[action]', function (e) {
            e.preventDefault();
            const action = $(this).attr('action');
            const data = $(this).data();
            if (
                action === 'tambah' ||
                action === 'edit' ||
                action === 'detail' ||
                action === 'pembayaran' ||
                action === 'import' ||
                action === 'export'
            ) {
                $('[form-menu]').removeClass('show');
                const modal = $(`[form-menu="${action}"]`);
                modal.addClass('show');
                $('#overlay').addClass('show');
                const form = modal.find('form');
                $.each(data, function (key, value) {
                    form.find(`[name="${key}"]`).val(value);
                    modal.find(`[data-detail="${key}"]`).text(value);
                });
            }
            if (
                action === 'delete' ||
                action === 'konfirmasi' ||
                action === 'aktif' ||
                action === 'nonaktif'
            ) {
                const config = {
                    delete: {
                        title: 'Yakin?',
                        text: 'Data akan dihapus',
                        confirm: 'Hapus'
                    },
                    konfirmasi: {
                        title: 'Konfirmasi Pembayaran?',
                        text: 'Pembayaran akan dikonfirmasi',
                        confirm: 'Konfirmasi'
                    },
                    aktif: {
                        title: 'Aktifkan Pelanggan?',
                        text: 'Pelanggan akan diaktifkan kembali',
                        confirm: 'Aktifkan'
                    },
                    nonaktif: {
                        title: 'Nonaktifkan Pelanggan?',
                        text: 'Pelanggan tidak akan mendapatkan tagihan lagi',
                        confirm: 'Nonaktifkan'
                    }
                };
                Swal.fire({
                    title: config[action].title,
                    text: config[action].text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: config[action].confirm
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: data.url,
                            type: 'POST',
                            data: {
                                id: data.id,
                                status: data.status,
                                [csrfName]: $(`input[name="${csrfName}"]`).val()
                            },
                            dataType: 'json',
                            success: function (res) {
                                if (res.token) {
                                    $(`input[name="${csrfName}"]`)
                                        .val(res.token);
                                }
                                Swal.fire({
                                    icon: res.status,
                                    title: res.message
                                });
                                loadData();
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                });
            }
            if (action === 'print') {
                window.open(
                    baseUrl + 'dashboard/pembayaran/print/' + data.id,
                    '_blank'
                );
            }
            if (action === 'export') {
                let bulan =
                    $('.bulan-data').val();
                let tahun =
                    $('.tahun-data').val();
                let url =
                    data.url +
                    '?bulan=' + bulan +
                    '&tahun=' + tahun;
                window.open(url);
            }
        });
    $(document)
        .off('submit', '[form-submit]')
        .on('submit', '[form-submit]', function (e) {
            e.preventDefault();
            let form = $(this);
            if (form.attr('form-submit') === 'profil') {
                return;
            }
            let formData = new FormData(this);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method') || 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    Swal.fire({
                        title: 'Loading...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (res) {
                    Swal.close();
                    if (res.token) {
                        $(`input[name="${csrfName}"]`)
                            .val(res.token);
                    }
                    Swal.fire({
                        icon: res.status,
                        title: res.message
                    });
                    if (res.status === 'success') {
                        loadData();
                        $('[form-menu]').removeClass('show');
                        $('#overlay').removeClass('show');
                        form[0].reset();
                    }
                },
                error: function (xhr) {
                    Swal.close();
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error'
                    });
                }
            });
        });
    $(document).on('click', '#btn-test', function () {
        let address = $('input[name="address"]').val();
        let username = $('input[name="username"]').val();
        let password = $('input[name="password"]').val();
        let port = $('input[name="port"]').val();
        $('#result-test').html('Testing connection...');
        $.ajax({
            url: baseUrl + 'dashboard/mikrotik/test',
            type: 'POST',
            data: {
                address: address,
                username: username,
                password: password,
                port: port,
                [csrfName]: $(`input[name="${csrfName}"]`).val()
            },
            dataType: 'json',
            success: function (response) {
                $(`input[name="${csrfName}"]`).val(response.token);
                $('#result-test').html(`
                <span class="${response.status}">
                    ${response.message}
                </span>
            `);
            },
            error: function (xhr) {
                $('#result-test').html(`
                <span class="error">
                    Connection failed
                </span>
            `);
            }
        });
    });
}
function initNotification() {
    function loadNotification() {
        $.ajax({
            url: baseUrl + 'dashboard/message',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                let html = '';
                $('#total-notification')
                    .text(res.total);
                $('.total-notification')
                    .text(res.total);
                res.data.forEach(item => {
                    if (item.is_read != 0) {
                        return;
                    }
                    let active =
                        item.is_read == 0
                            ? 'unread'
                            : '';
                    html += `
                        <li class="notification-item ${active}"
                            data-id="${item.id_message}">

                            <div class="notification-message">
                                ${item.message}
                            </div>

                            <div class="notification-date">
                                ${item.created_at}
                            </div>

                        </li>
                    `;
                });

                if (html === '') {

                    html = `
                        <div class="notification-empty">
                            Tidak ada notifikasi
                        </div>
                    `;
                }

                $('#notification-list').html(html);
            },

            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    loadNotification();

    setInterval(() => {
        loadNotification();
    }, 3000);

    $(document).off('click', '.notification-item').on(
        'click',
        '.notification-item',
        function () {
            let id = $(this).data('id');
            $.ajax({
                url: baseUrl + 'dashboard/message/read-all',
                type: 'POST',
                data: {
                    id: id,
                    [csrfName]:
                        $(`input[name="${csrfName}"]`).val()
                },
                dataType: 'json',
                success: function (res) {
                    if (res.token) {
                        $(`input[name="${csrfName}"]`)
                            .val(res.token);
                    }
                    loadNotification();
                }
            });
        }
    );
}

window.loadData = function (page = 1, limit = null, search = null, bulan = null, tahun = null) {
    let url = $('.table').data('url');
    let totalColumns = $('.table thead th').length;
    limit = limit ?? $('.filter-data').val();
    search = search ?? $('.search-data').val();
    bulan = bulan ?? $('.bulan-data').val();
    tahun = tahun ?? $('.tahun-data').val();
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        data: {
            page: page,
            limit: limit,
            search: search,
            bulan: bulan,
            tahun: tahun
        },
        cache: false,
        beforeSend: function () {
            $('.table-body').html(`
                <tr>
                    <td colspan="${totalColumns}" 
                        style="text-align:center;">
                        Loading...
                    </td>
                </tr>
            `);
        },
        success: function (res) {
            $('.table-body')
                .hide()
                .html(res.html)
                .fadeIn(150);
            $('.custom-pagination')
                .html(res.pagination);
            $('.showing-data')
                .html(res.showing);
        }
    });
};

loadData();

$(document).on(
    'click',
    '.pagination-btn',
    function () {
        let page = $(this).data('page');
        loadData(
            page,
            $('.filter-data').val()
        );
    }
);

$(document).on(
    'change',
    '.filter-data',
    function () {
        let limit = $(this).val();
        loadData(limit);
    }
);
let searchTimer;

$(document).on(
    'keyup',
    '.search-data',
    function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            loadData({
                page: 1,
                search: $(this).val()
            });
        }, 500);
    }
);

$(document).on(
    'click',
    '.filter button',
    function () {
        loadData({
            page: 1,
            bulan: $('.bulan-data').val(),
            tahun: $('.tahun-data').val()
        });
    }
);

$(document).on(
    'keyup',
    '#id_pelanggan',
    function () {
        let id = $(this).val();
        // kosongkan nama jika input kosong
        if (id === '') {
            $('#nama').val('');
            return;
        }
        $.ajax({
            url: baseUrl + 'dashboard/perbaikan/pelanggan/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.status) {
                    $('#nama').val(
                        res.data.nama
                    );
                } else {
                    $('#nama').val('');
                }
            },
            error: function () {
                $('#nama').val('');
            }
        });
    });

$(document).ajaxComplete(function (event, xhr) {
    try {
        let res = xhr.responseJSON || JSON.parse(xhr.responseText);
        updateCSRF(res);
    } catch (e) { }
});

function updateCSRF(res) {
    if (!res || !res.csrf_token || !res.csrf_name) return;
    $(`input[name="${res.csrf_name}"]`).val(res.csrf_token);
}

function rupiah() {
    $('#tarif_view').on('input', function () {
        let angka = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(angka);
        $('#tarif_real').val(angka);
    });
}

function initFileUpload() {
    const fileInputs = document.querySelectorAll('[data-file]');
    if (!fileInputs.length) return;
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const target = this.getAttribute('data-file');
            const fileName = document.querySelector(
                `[data-file-name="${target}"]`
            );
            if (!fileName) return;
            if (this.files.length > 0) {
                fileName.value = this.files[0].name;
            } else {
                fileName.value = '';
            }
        });
    });
}