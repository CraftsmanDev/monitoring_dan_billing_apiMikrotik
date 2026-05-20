<?php

namespace App\Controllers;
use App\Models\Paket_model;
use App\Service\MikrotikService;
use App\Repositories\Mikrotik\MikrotikRepo;

class Paket extends BaseController
{
    protected $model;
    private MikrotikService $service;
    public function __construct()
    {
        $repo = new MikrotikRepo;
        $this->service = new MikrotikService($repo);
        $this->model = new Paket_model();
    }
    
    public function index()
    {
        return view('component/paket_layanan/index', [
            'title' => 'Paket Layanan',
        ]);
    }

    public function fetch()
    {
    $page = (int) ($this->request->getGet('page') ?? 1);
    $limit = (int) ($this->request->getGet('limit') ?? 10);
    $search = $this->request->getGet('search');
    $offset = ($page - 1) * $limit;
    $total = $this->model->countData($search);
    $totalPage = ceil($total / $limit);
    if ($page > $totalPage && $totalPage > 0) {
        $page = 1;
        $offset = 0;
    }
    $data = $this->model->getData(
        $limit,
        $offset,
        $search
    );
        $html = '';
        foreach ($data as $i => $row) {
            $number = $offset + $i + 1;
            $html .= '
                <tr>
                    <td>'.$number.'</td>
                    <td>'.$row['nama_paket'].'</td>
                    <td>'.$row['kecepatan'].'</td>
                    <td>Rp '.number_format($row['tarif'], 0, ',', '.').'</td>
                    <td class="action-2">
                        <button action="edit" style="--action-color: #0e75fb;"
                            data-id="'.$row['id_paket'].'"
                            data-nama_paket="'.$row['nama_paket'].'"
                            data-kecepatan="'.$row['kecepatan'].'"
                            data-tarif="'.$row['tarif'].'">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button action="delete" 
                        style="--action-color: #FD6666;" 
                        data-id="'.$row['id_paket'].'"
                        data-url="'.base_url('dashboard/paket_layanan/delete').'">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            ';
        }
        if (empty($data)) {
            $html = '
                <tr>
                    <td colspan="5"style="text-align: center;">Data tidak ditemukan</td>
                </tr>
            ';
        }
        $start = $total ? $offset + 1 : 0;
        $end = $offset + count($data);
        if ($end > $total) {
            $end = $total;
        }
        $pagination = custom_pagination(
            $page,
            $totalPage
        );
        $showing = "
            Showing {$start} to {$end} of {$total} entries
        ";
        return $this->response->setJSON([
            'html' => $html,
            'pagination' => $pagination,
            'showing'    => $showing
        ]);
    }

    public function store()
    {
        $res = $this->service->createProfile(
            $this->request->getPost('nama_paket'),
            $this->request->getPost('kecepatan'),
            $this->request->getPost('tarif')
        );

        return $this->response->setJSON(
            json_response(
                $res['status'] ? 'success' : 'error',
                $res['message'],
                $res['data'] ?? []
            )
        );
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $paket_lama = $this->model->find($id);

        if (!$paket_lama) {
            return $this->response->setJSON(
                json_response('error', 'Data tidak ditemukan')
            );
        }

        $oldName = $paket_lama['nama_paket'];

        $nama_paket = $this->request->getPost('nama_paket');
        $kecepatan  = $this->request->getPost('kecepatan');
        $tarif      = $this->request->getPost('tarif');

        $res = $this->service->updateProfile(
            $oldName,
            $nama_paket,
            $kecepatan,
            $tarif,
            $id
        );

        return $this->response->setJSON(
            json_response(
                $res['status'] ? 'success' : 'error',
                $res['message'],
                $res['data'] ?? []
            )
        );
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $paket = $this->model->find($id);

        if (!$paket) {
            return $this->response->setJSON(
                json_response('error', 'Data tidak ditemukan')
            );
        }

        $nama_paket = $paket['nama_paket'];

        $res = $this->service->deleteProfile($nama_paket, $id);

        if ($res['status']) {
            $this->model->delete($id);
        }

        return $this->response->setJSON(
            json_response(
                $res['status'] ? 'success' : 'error',
                $res['message'],
                $res['data'] ?? []
            )
        );
    }
}
