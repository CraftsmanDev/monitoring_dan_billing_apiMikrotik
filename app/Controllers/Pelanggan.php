<?php

namespace App\Controllers;
use App\Models\Paket_model;
use App\Models\Pelanggan_model;
use App\Models\Pembayaran_Model;
use App\Service\MikrotikService;
use App\Repositories\Mikrotik\MikrotikRepo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Pelanggan extends BaseController
{
    protected $model;
    protected $pelanggan;
    private MikrotikService $service;
    public function __construct(){
        $this->pelanggan = new Pelanggan_model();
        $repo = new MikrotikRepo;
        $this->service = new MikrotikService($repo);
        $this->model = new Paket_model();
    }

    public function index()
    {
        $paketModel = new Paket_model();
        $pembayaran = new Pembayaran_Model();

        $res = $this->service->getIsolir();
        $total_lunas = $pembayaran
        ->where('status_pembayaran', 'lunas')
        ->countAllResults();

        $isolir = $res['status'] ? $res['data'] : [];

        $data = [
            'paket' => $paketModel->findAll(),
            'title' => 'Pelanggan',
            'isolir' => $isolir,
            'sudah_bayar' => $total_lunas
        ];

        return view('component/pelanggan/index', $data);
    }
    
    public function fetch()
    {
    $role = session()->get('role');
    $page = (int) ($this->request->getGet('page') ?? 1);
    $limit = (int) ($this->request->getGet('limit') ?? 10);
    $search = $this->request->getGet('search');

    $offset = ($page - 1) * $limit;
    $total = $this->pelanggan->countAll();
    $totalPage = ceil($total / $limit);
    if ($page > $totalPage && $totalPage > 0) {
        $page = 1;
        $offset = 0;
    }
    $data = $this->pelanggan->getData($limit,$offset,$search);
        $html = '';
        foreach ($data as $i => $row) {
            $number = $offset + $i + 1;
            $action = '';
            if ($role == 'admin') {
                $statusBtn = '';
                if ($row['status'] == 'AKTIF') {
                    $statusBtn = '
                    <button action="nonaktif"
                    style="--action-color:#22c55e ;"
                    data-id="'.$row['id_pelanggan'].'"
                    data-status="NONAKTIF"
                    data-url="'.base_url('dashboard/pelanggan/status').'">
                        <i class="fa-solid fa-user-check"></i>
                    </button>
                    ';
                } else {
                    $statusBtn = '
                    <button action="aktif"
                    style="--action-color:#f59e0b;"
                    data-id="'.$row['id_pelanggan'].'"
                    data-status="AKTIF"
                    data-url="'.base_url('dashboard/pelanggan/status').'">
                        <i class="fa-solid fa-user-slash"></i>
                    </button>
                    ';
                }

                $action = '
                <td class="action-2">
                    <button action="edit" style="--action-color: #0e75fb;"
                    data-id_pelanggan="'.$row['id_pelanggan'].'"
                    data-nama="'.$row['nama'].'"
                    data-area="'.$row['area'].'"
                    data-nomor_wa="'.$row['nomor_wa'].'"
                    data-paket_id="'.$row['paket_id'].'"
                    data-tanggal_register="'.$row['tanggal_register'].'">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>

                    '.$statusBtn.'

                    <button action="delete" 
                    style="--action-color: #FD6666;" 
                    data-id="'.$row['id_pelanggan'].'"
                    data-url="'.base_url('dashboard/pelanggan/delete').'">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
                ';
            }
            $html .= '
                <tr>
                    <td>'.$number.'</td>
                    <td>'.$row['id_pelanggan'].'</td>
                    <td>'.$row['nama'].'</td>
                    <td>'.$row['nomor_wa'].'</td>
                    <td>'.$row['area'].'</td>
                    <td>'.$row['nama_paket'].'</td>
                    <td>Rp '.number_format($row['tarif'], 0, ',', '.').'</td>
                    <td>'.$row['tanggal_register'].'</td>
                    '.$action.'
                </tr>
            ';
        }
        if (empty($data)) {
            $html = '
                <tr>
                    <td colspan="8" style="text-align: center;">Data tidak ditemukan</td>
                </tr>
            ';
        }
        $start = $total ? $offset + 1 : 0;
        $end = $offset + count($data);
        if ($end > $total) {
            $end = $total;
        }
        $pagination = custom_pagination($page,$totalPage);
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
        $paket_id = $this->request->getPost('paket_id');
        $paket = $this->model->find($paket_id);
        if (!$paket) {
            return $this->response->setJSON(
                json_response('error', 'Paket tidak ditemukan')
            );
        }   
        $nama_paket = $paket['nama_paket'];
        $tarif = $paket['tarif'];
        $password = '123';
        $res = $this->service->createSecret(
            $this->request->getPost('nama'),
            $nama_paket,
            $password,
            (int)$paket_id,
            $this->request->getPost('id_pelanggan'),
            $this->request->getPost('area'),
            date(
                'Y-m-d',
                strtotime($this->request->getPost('tanggal_register'))
            ),
            $this->request->getPost('nomor_wa'),
            $tarif
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
        $id = $this->request->getPost('id_pelanggan');

        $paket_id = $this->request->getPost('paket_id');

        $paketModel = new Paket_model();

        $paket = $paketModel->find($paket_id);
        $pelanggan_lama = $this->pelanggan->find($id);

        if (!$paket) {
            return $this->response->setJSON(
                json_response('error', 'Paket tidak ditemukan')
            );
        }

        if (!$pelanggan_lama) {
            return $this->response->setJSON(
                json_response('error', 'pelanggan tidak ditemukan')
            );
        }

        $oldNama = $pelanggan_lama['nama'];

        $nama_paket = $paket['nama_paket'];

        $password = '123';

        $res = $this->service->updateSecret(
            $id,
            $oldNama,
            $this->request->getPost('nama'),
            $nama_paket,
            $password,
            (int)$paket_id,
            $this->request->getPost('area'),
            date(
                'Y-m-d',
                strtotime($this->request->getPost('tanggal_register'))
            ),
            $this->request->getPost('nomor_wa')
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

        $pelanggan = $this->pelanggan->find($id);

        if (!$pelanggan) {
            return $this->response->setJSON(
                json_response('error', 'Data pelanggan tidak ditemukan')
            );
        }
        $pembayaran = new Pembayaran_Model();

        $cekPembayaran = $pembayaran
            ->where('id_pelanggan', $id)
            ->countAllResults();

        if ($cekPembayaran > 0) {
            return $this->response->setJSON(
                json_response(
                    'error',
                    'Pelanggan memiliki histori pembayaran'
                )
            );
        }

        $res = $this->service->deleteSecret(
            $id,
            $pelanggan['nama']
        );

        return $this->response->setJSON(
            json_response(
                $res['status'] ? 'success' : 'error',
                $res['message']
            )
        );
    }

    public function importExcel()
    {
        try {

            $mode = $this->request->getPost('mode_import');

            $file = $this->request->getFile('file_excel');

            if (!$file->isValid()) {

                return $this->response->setJSON(
                    json_response('error', 'File tidak valid')
                );
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
                $file->getTempName()
            );

            $sheet = $spreadsheet
                ->getActiveSheet()
                ->toArray();

            $success = 0;
            $failed = 0;
            $errors = [];

            foreach ($sheet as $key => $row){

                // skip header
                if ($key == 0) {
                    continue;
                }

                $id_pelanggan = trim($row[0]);
                $nama = trim($row[1]);
                $nomor_wa = trim($row[2]);
                $area = trim($row[3]);
                $paket = trim($row[4]);

                $tanggal_register = date(
                    'Y-m-d',
                    strtotime($row[5])
                );

                $res = $this->service->Excelsecret(
                    $id_pelanggan,
                    $nama,
                    $nomor_wa,
                    $area,
                    $paket,
                    $tanggal_register,
                    $mode
                );

                if ($res['status']) {

                    $success++;

                } else {

                    $failed++;

                    $errors[] = [
                        'baris' => $key + 1,
                        'pelanggan' => $nama,
                        'error' => $res['message']
                    ];
                }
            }

            return $this->response->setJSON(
                json_response(
                    'success',
                    "Import selesai. Berhasil: $success, Gagal: $failed",
                    $errors
                )
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                json_response(
                    'error',
                    $e->getMessage()
                )
            );
        }
    }

    public function status()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $data = [
            'status' => $status,
            'tanggal_berhenti' =>
                $status == 'NONAKTIF'
                ? date('Y-m-d')
                : null
        ];
        $this->pelanggan
            ->where('id_pelanggan', $id)
            ->set($data)
            ->update();
        return $this->response->setJSON(
            json_response(
                'success',
                'Status pelanggan berhasil diubah'
            )
        );
    }
    
    public function exportTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'id_pelanggan');
        $sheet->setCellValue('B1', 'nama');
        $sheet->setCellValue('C1', 'nomor_wa');
        $sheet->setCellValue('D1', 'area');
        $sheet->setCellValue('E1', 'nama_paket');
        $sheet->setCellValue('F1', 'tanggal_register');


        $sheet->setCellValue('A2', 'PLG001');
        $sheet->setCellValue('B2', 'Rahmat');
        $sheet->setCellValue('C2', "'08123456789");
        $sheet->setCellValue('D2', 'Area 1');
        $sheet->setCellValue('E2', '10 Mbps');
        $sheet->setCellValue('F2', '2026-05-11');

        $sheet->getStyle('A1:F1')
            ->getFont()
            ->setBold(true);

        foreach (range('A', 'F') as $column) {

            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }

        $filename = 'Template_Import_Pelanggan.xlsx';

        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment; filename="' . $filename . '"'
        );

        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        $writer->save('php://output');

        exit;
    }
}
