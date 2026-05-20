<?php

namespace App\Controllers;
use App\Models\Perbaikan_model;
use App\Models\Message_model;
use App\Service\MikrotikService;
use App\Repositories\Mikrotik\MikrotikRepo;

class Perbaikan extends BaseController
{
    protected $model;

    public function __Construct()
    {
        $this->model = new Perbaikan_model();
    }
    public function index()
    {
        return view('component/perbaikan/index', [
            'title' => 'Perbaikan',
        ]);
    }

    public function fetch()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 10);
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $search = $this->request->getGet('search');
        $offset = ($page - 1) * $limit;
        $total = $this->model->countData($search, $bulan, $tahun);
        $totalPage = ceil($total / $limit);
        if ($page > $totalPage && $totalPage > 0) {
            $page = 1;
            $offset = 0;
        }
        $data = $this->model->getData(
            $limit,
            $offset,
            $search,
            $bulan,
            $tahun
        );

        $html = '';
        foreach ($data as $i => $row) {
            $number = $offset + $i + 1;
            $html .= '
                <tr>
                    <td>'.$number.'</td>
                    <td>'.$row['id_pelanggan'].'</td>
                    <td>'.$row['nama'].'</td>
                    <td>'.$row['kategori'].'</td>
                    <td>'.$row['anggaran'].'</td>
                    <td>'.date('d-m-Y', strtotime($row['tanggal'])).'</td>
                    <td>'.$row['status'].'</td>
                    <td>'.$row['keterangan'].'</td>
                    <td class="action-2">
                        <button 
                        action="edit"
                        data-id="'.$row['id_perbaikan'].'"
                        data-id_pelanggan="'.$row['id_pelanggan'].'"
                        data-nama="'.$row['nama'].'"
                        data-kategori="'.$row['kategori'].'"
                        data-anggaran="'.$row['anggaran'].'"
                        data-tanggal="'.$row['tanggal'].'"
                        data-status="'.$row['status'].'"
                        data-keterangan="'.$row['keterangan'].'"
                        style="--action-color: #0e75fb;
                        ">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button 
                        action="delete" 
                        style="--action-color: #FD6666;" 
                        data-id="'.$row['id_perbaikan'].'"
                        data-url="'.base_url('dashboard/perbaikan/delete').'">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            ';
        }

        if (empty($data)) {
            $html = '
                <tr>
                    <td colspan="9"style="text-align: center;">Data tidak ditemukan</td>
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
            'showing'   => $showing
        ]);
    }

    public function store()
    {
        try {
            $id_perbaikan = $this->model->insert([
                'id_pelanggan' =>$this->request->getPost('id_pelanggan'),
                'kategori' =>$this->request->getPost('kategori'),
                'status' => 'pending',
                'tanggal' =>$this->request->getPost('tanggal'),
                'anggaran' =>str_replace('.','',$this->request->getPost('anggaran' )),
                'keterangan' =>$this->request->getPost('keterangan')
            ]);
            $data = $this->model
            ->select('perbaikan.*, pelanggan.nama')
            ->join('pelanggan', 'pelanggan.id_pelanggan = perbaikan.id_pelanggan')
            ->where('id_perbaikan', $id_perbaikan)
            ->first();
            $message = new Message_model();
            $message->insert([
                'message' =>
                    'Pelanggan atas nama ' .
                    $data['nama'] .
                    ' melaporkan gangguan ' .
                    $data['kategori'],
                'is_read' => 0,
                'status' => 'unread',
                'id_users' => session()->get('id_users')
            ]);
            return $this->response
                ->setJSON(
                    json_response('success', 'Data perbaikan berhasil ditambahkan')
                );
        } catch (\Throwable $e) {
            return $this->response
                ->setJSON(
                    json_response( 'error',$e->getMessage())
                );
        }
    }

    public function getPelanggan($id)
    {
        $pelanggan = new \App\Models\Pelanggan_model();
        $data = $pelanggan
            ->where(
                'id_pelanggan',
                $id
            )
            ->first();
        if (!$data) {
            return $this->response
                ->setJSON([
                    'status' => false,
                    'message' =>
                        'Pelanggan tidak ditemukan'
                ]);
        }
        return $this->response
            ->setJSON([
                'status' => true,
                'data' => $data
            ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $this->model->update($id, [
            'id_pelanggan' => $this->request->getPost('id_pelanggan'),
            'kategori' => $this->request->getPost('kategori'),
            'status' => $status,
            'tanggal' => $this->request->getPost('tanggal'),
            'anggaran' => str_replace('.', '', $this->request->getPost('anggaran')),
            'keterangan' => $this->request->getPost('keterangan')
        ]);

        return $this->response->setJSON(
            json_response(
                'success',
                'Data perbaikan berhasil diperbarui'
            )
        );
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(
                json_response('error', 'ID tidak ditemukan')
            );
        }
        $this->model->delete($id);
        return $this->response->setJSON(
            json_response(
                'success',
                'Data perbaikan berhasil dihapus'
            )
        );
    }
}