<?php

namespace App\Controllers;
use App\Models\Mikrotik_model;
use App\Models\Pelanggan_model;
use App\Models\Pembayaran_Model;
use App\Models\Perbaikan_model;
use App\Service\MikrotikService;
use App\Repositories\Mikrotik\MikrotikRepo;

class Dashboard extends BaseController
{
    protected $helpers = [
        'format'
    ];
    
    private MikrotikService $service;
    public function __construct()
    {
        $repo = new MikrotikRepo;
        $this->service = new MikrotikService($repo);
    }
    public function index()
    {
        $data = [
            "title" => "Dashboard",
        ];

        return view('component/index', $data);
    }

    public function count()
    {
        $pelanggan = new Pelanggan_model();
        $pembayaran = new Pembayaran_Model();
        $pengeluaran = new Perbaikan_model();

        $total_pelanggan = $pelanggan->countAll();
        $total_pemasukan = $pembayaran
            ->selectSum('total_tagihan')
            ->where('status_pembayaran', 'lunas')
            ->first();

        $total_pengeluaran = $pengeluaran
            ->selectSum('anggaran')
            ->first();

        $pemasukan = (int) (
            $total_pemasukan['total_tagihan'] ?? 0
        );

        $pengeluaran = (int) (
            $total_pengeluaran['anggaran'] ?? 0
        );

        $total_pendapatan = $pemasukan - $pengeluaran;

        $cpu = $this->service->getCpu();

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'total_pelanggan'   => $total_pelanggan,
                'total_pemasukan'   => $pemasukan,
                'total_pengeluaran' => $pengeluaran,
                'total_pendapatan'  => $total_pendapatan,
                'cpu_load'          => $cpu['data']['cpu_load'] ?? 0
            ]
        ]);
    }

    public function active()
    {
        $res = $this->service->getActivePpp();
        return $this->response->setJSON($res);
    }

    public function traffic()
    {
        $res = $this->service
        ->getTraffic('ether1');

        if (!$res['status']) {

            return $this->response
                ->setJSON([
                    'download' => 0,
                    'upload' => 0
            ]);
        }

    return $this->response
        ->setJSON(
            $res['data']
        );
    }
}
