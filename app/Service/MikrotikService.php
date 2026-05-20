<?php

namespace App\Service;

use App\Repositories\Mikrotik\MikrotikRepoInterface;
use App\Models\Mikrotik_model;
use App\Models\Paket_model;
use App\Models\Pelanggan_model;
use App\Models\Pembayaran_Model;
use App\Models\Perbaikan_model;
use App\Models\Users_Model;
use App\Models\Monitoring;
use App\Service\whatsappService;

class MikrotikService
{
    private MikrotikRepoInterface $repo;

    public function __construct(MikrotikRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    private function routers(): array
    {
        return (new Mikrotik_model())->findAll();
    }

    public function createProfile(string $name, string $rate, int $tarif): array
    {
        foreach ($this->routers() as $router) {
            $this->repo->addProfile($router, $name, $rate);
        }

        $paket_model = new Paket_model;

        $paket_model->insert([
            'nama_paket' => $name,
            'kecepatan'  => $rate,
            'tarif'      => $tarif
        ]);

       return [
            'status'  => true,
            'message' => 'Profile dan paket berhasil dibuat',
            'data'    => []
        ];
    }

    public function updateProfile(string $oldName, string $newName, string $rate, int $tarif, int $id): array
    {
        try {
            foreach ($this->routers() as $router) {
                $this->repo->updateProfile($router, $oldName, $newName, $rate);
            }

            $paket_model = new Paket_model;

            $paket_model->update($id, [
                'nama_paket' => $newName,
                'kecepatan'  => $rate,
                'tarif'      => $tarif
            ]);

            return [
                'status'  => true,
                'message' => 'Profile dan paket berhasil dibuat',
                'data'    => []
            ];
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());

            return [
                'status' => false,
                'message' => 'Gagal update data'
            ];
        }
    }

    public function deleteProfile(string $name, int $id): array
    {
        try {
            foreach ($this->routers() as $router) {
                $this->repo->deleteProfile($router, $name);
            }
            $paket_model = new Paket_model();
            $paket_model->delete($id);
            return [
                'status'  => true,
                'message' => 'Profile berhasil dihapus',
                'data'    => []
            ];
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return [
                'status'  => false,
                'message' => 'Gagal menghapus data'
            ];
        }
    }

    //secret
    public function getIsolir(): array
    {
        try {
            $allIsolir = [];

            foreach ($this->routers() as $router) {

                $res = $this->repo->getSecret($router);

                if (!$res['status']) {
                    continue;
                }

                $filtered = array_filter($res['data'], function ($item) {
                    return ($item['profile'] ?? '') === 'ISOLIR';
                });

                $allIsolir = array_merge($allIsolir, $filtered);
            }

            return [
                'status' => true,
                'message' => 'Data isolir berhasil diambil',
                'data' => array_values($allIsolir)
            ];

        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());

            return [
                'status' => false,
                'message' => 'Gagal mengambil isolir',
                'data' => []
            ];
        }
    }

    public function createSecret(string $name,string $profil,string $password,int $paket_id,string $id_pelanggan,string $area,string $tanggal_register,string $nomor_wa, string $tarif): array
    {
        try {
            foreach ($this->routers() as $router) {
                $this->repo->addSecret(
                    $router,
                    $name,
                    $profil,
                    $password
                );
            }
            $tanggal_register = date(
                'Y-m-d',
                strtotime($tanggal_register)
            );
            $tanggal_mulai_tagihan = date(
                'Y-m-10 H:i:s',
                strtotime('+1 month', strtotime($tanggal_register))
            );

            $pelanggan = new Pelanggan_model();
            $pelanggan->insert([
                'id_pelanggan'         => $id_pelanggan,
                'nama'                 => $name,
                'area'                 => $area,
                'nomor_wa'             => $nomor_wa,
                'paket_id'             => $paket_id,
                'tanggal_register'     => $tanggal_register,
                'tanggal_mulai_tagihan'=> $tanggal_mulai_tagihan
            ]);

            $pembayaran = new Pembayaran_Model();

            $pembayaran->insert([
                'invoice'            => 'INV-' . date('Ym') . '-' . rand(1000,9999),
                'id_pelanggan'       => $id_pelanggan,
                'periode'            => date('Y-m'),
                'tanggal_tagihan'    => date('Y-m-d'),
                'jatuh_tempo'        => date('Y-m-10', strtotime('+1 month')),
                'metode_pembayaran'  => 'CASH',
                'total_tagihan'      => $tarif,
                'total_bayar'        => $tarif,
                'status_pembayaran'  => 'LUNAS',
                'tanggal_bayar'      => date('Y-m-d'),
                'keterangan'         => 'Pembayaran pemasangan pelanggan baru'
            ]);

            return [
                'status' => true,
                'message' => 'Data pelanggan berhasil disimpan',
                'data' => []
            ];

        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateSecret(
        string $id_pelanggan,
        string $oldName,
        string $newName,
        string $profile,
        string $password,
        int $paket_id,
        string $area,
        string $tanggal_register,
        string $nomor_wa
    ): array
    {
        try {

            foreach ($this->routers() as $router) {

                $this->repo->updateSecret(
                    $router,
                    $oldName,
                    $newName,
                    $profile,
                    $password
                );
            }

            $pelanggan = new Pelanggan_model();

            $pelanggan->update($id_pelanggan, [
                'nama'              => $newName,
                'paket_id'          => $paket_id,
                'id_pelanggan'      => $id_pelanggan,
                'area'              => $area,
                'tanggal_register'  => $tanggal_register,
                'nomer_wa'          => $nomor_wa
            ]);

            return [
                'status' => true,
                'message' => 'Data pelanggan berhasil diupdate',
                'data' => []
            ];

        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());

            return [
                'status' => false,
                'message' => 'Gagal update data pelanggan'
            ];
        }
    }

    public function deleteSecret(string $id_pelanggan, string $name): array
    {
        try {
            foreach ($this->routers() as $router) {
                $this->repo->deleteSecret($router, $name);
            }

            $pelanggan = new Pelanggan_model();
            $pelanggan->delete($id_pelanggan);

            return [
                'status' => true,
                'message' => 'Data berhasil dihapus',
                'data' => []
            ];

        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());

            return [
                'status' => false,
                'message' => 'Gagal menghapus data'
            ];
        }
    }

    public function Excelsecret(string $id_pelanggan,string $name, string $nomor_wa,string $area,string $paket,string $tanggal_register, string $mode):array
    {
        try {
            $pelanggan = new Pelanggan_model();
            $paket_layanan = new Paket_model();

            $dataPaket = $paket_layanan
                    ->where('nama_paket', $paket)
                    ->first();

            if (!$dataPaket) {
                return [
                    'status' => false,
                    'message'=> 'Paket layanan tidak ditemukan'
                ];
            }

            $paket_id = $dataPaket['id_paket'];

            $cek = $pelanggan
            ->where('id_pelanggan', $id_pelanggan)
            ->countAllResults();

            if ($cek > 0) {
                return [
                    'status' => false,
                    'message'=> 'ID pelanggan sudah ada'
                ];
            }
            if ($mode == 'database') {
                $pelanggan->insert([
                    'id_pelanggan' => $id_pelanggan,
                    'nama'         => $name,
                    'nomor_wa'     => $nomor_wa,
                    'area'         => $area,
                    'paket_id'     => $paket_id,
                    'tanggal_register' => $tanggal_register
                ]);

                return [
                    'status' => true,
                    'message'=> 'Berhasil tambah database'
                ];
            }

            if ($mode == 'both') {
                $pelanggan->insert([
                    'id_pelanggan' => $id_pelanggan,
                    'nama'         => $name,
                    'nomor_wa'     => $nomor_wa,
                    'area'         => $area,
                    'paket_id'     => $paket_id,
                    'tanggal_register' => $tanggal_register
                ]);

                foreach ($this->routers() as $router) {
                    $this->repo->addSecret(
                        $router,
                        $name,
                        $paket,
                        '123'
                    );
                }

                return [
                    'status' => true,
                    'message'=> 'Berhasil tambah database dan mikrotik'
                ];
            }

            return [
                'status' => false,
                'message' => 'Mode tidak valid'
            ];

        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getTraffic(string $interface = 'ether1'): array
    {
        try {
            $routers = $this->routers();
            if (empty($routers)) {
                return [
                    'status' => false,
                    'message' => 'Router tidak ditemukan'
                ];
            }
            $router = $routers[0];
            $res = $this->repo
                ->getInterfaceTraffic(
                    $router,
                    $interface
                );
            if (!$res['status']) {
                return $res;
            }
            $data = $res['data'][0] ?? [];
            $download =
                round(
                    (($data['rx-bits-per-second'] ?? 0)
                    / 1024 / 1024),
                    2
                );
            $upload =
                round(
                    (($data['tx-bits-per-second'] ?? 0)
                    / 1024 / 1024),
                    2
                );
            return [
                'status' => true,
                'data' => [
                    'download' => $download,
                    'upload' => $upload
                ]
            ];
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function detectServerDown(): array
    {
        try {
            $routers = $this->routers();
            if (empty($routers)) {
                return [
                    'status' => false,
                    'message' => 'Router tidak ditemukan'
                ];
            }
            $router = $routers[0];
            $timeout = 0;
            for ($i = 0; $i < 3; $i++) {
                $ping = $this->repo->pingHost(
                    $router,
                    '8.8.8.8'
                );
                if (
                    !$ping['status'] ||
                    empty($ping['data'])
                ) {
                    $timeout++;
                } else {
                    // cek apakah reply benar-benar ada
                    $reply = $ping['data'][0]['received'] ?? 0;
                    if ($reply == 0) {
                        $timeout++;
                    }
                }
                // delay 2 detik
                sleep(2);
            }
            if ($timeout >= 3) {
                $statusRouter = 'DOWN';
            } else {
                $statusRouter = 'UP';
            }
            $monitor = new Monitoring();
            $wa = new WhatsappService();
            $pelanggan = new Pelanggan_model();
            $existing = $monitor
                ->where('device_name', )
                ->first();
            if (!$existing) {
                $monitor->insert([
                    'device_name' => 'MAIN_ROUTER',
                    'status' => $statusRouter
                ]);
                return [
                    'status' => true,
                    'message' => 'Status awal disimpan'
                ];
            }
            if ($existing['status'] != $statusRouter) {
                $monitor->update($existing['id'], [
                    'status' => $statusRouter,
                    'last_notified' => date('Y-m-d H:i:s')
                ]);
                $allPelanggan = $pelanggan->findAll();
                foreach ($allPelanggan as $p) {
                    $nomor = $p['nomor_wa'];
                    if ($statusRouter == 'DOWN') {
                        $message =
                        "INFORMASI GANGGUAN\n\n".
                        "Mohon maaf terjadi gangguan jaringan internet.\n".
                        "Tim teknis sedang melakukan perbaikan.\n\n".
                        "Terima kasih.";
                    } else {
                        $message =
                        "INFORMASI JARINGAN NORMAL\n\n".
                        "Gangguan jaringan telah selesai diperbaiki.\n".
                        "Internet sudah kembali normal.\n\n".
                        "Terima kasih.";
                    }
                    $wa->send(
                        $nomor,
                        $message
                    );
                    sleep(
                        rand(2, 5)
                    );
                }
                return [
                    'status' => true,
                    'message' => 'Notifikasi WA berhasil dikirim'
                ];
            }
            return [
                'status' => true,
                'message' => 'Tidak ada perubahan status'
            ];
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getCpu(): array
    {
        try {

            $router = $this->routers()[0] ?? null;

            if (!$router) {
                return [
                    'status' => false,
                    'message' => 'Router tidak ditemukan'
                ];
            }

            $res = $this->repo->getSystemResource($router);

            $data = $res['data']['data'][0] ?? [];

            return [
                'status' => true,
                'data' => [
                    'cpu_load' => (int) ($data['cpu-load'] ?? 0)
                ]
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getActivePpp(): array
    {
        helper('format');
        try {
            $router = $this->routers()[0] ?? null;
            if (!$router) {
                return [
                    'status' => false,
                    'message' => 'Router tidak ditemukan'
                ];
            }
            $res = $this->repo->getActive($router);
            $data = $res['data']['data'] ?? [];
            $result = [];
            foreach ($data as $item) {
                $result[] = [
                    'username' => $item['name'] ?? '-',
                    'ip'       => $item['address'] ?? '-',
                    'uptime'   => $item['uptime'] ?? '-',
                    'service'  => $item['service'] ?? '-',
                    'caller'   => $item['caller-id'] ?? '-',
                    'download' => formatBytes(
                        $item['rx-byte'] ?? 0
                    ),
                    'upload' => formatBytes(
                        $item['tx-byte'] ?? 0
                    ),
                    'rate'     => $item['rate-limit'] ?? '-',
                    'status'   => 'Online'
                ];
            }
            return [
                'status' => true,
                'total'  => count($result),
                'data'   => $result
            ];
        } catch (\Throwable $e) {

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getActive(): array
    {
        $result = [];

        foreach ($this->routers() as $router) {
            $res = $this->repo->getActive($router);

            if ($res['status']) {
                $result[] = $res['data'];
            }
        }

        return [
            'status' => true,
            'data' => $result
        ];
    }
}