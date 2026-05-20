<?php

namespace App\Controllers;
use App\Models\Pembayaran_Model;
use App\Models\Pelanggan_model;
use App\Models\Paket_model;
use App\Models\Message_model;

class Pembayaran extends BaseController
{
    protected $pembayaran;
    protected $pelanggan;

    public function __Construct(){
        $this->pembayaran = new Pembayaran_Model();
        $this->pelanggan = new Pelanggan_model();
    }
    public function index()
    {
        $paketModel = new Paket_model();
        $data = [
            'title' => 'pembayaran',
            'paket' => $paketModel->findAll()
        ];
        return view('component/pembayaran/index', $data);
    }

    public function fetch()
    {
        $role = session()->get('role');
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 10);
        $search = $this->request->getGet('search');
        $offset = ($page - 1) * $limit;
        $total = $this->pembayaran->countData($search);
        $totalPage = ceil($total / $limit);
        if ($page > $totalPage && $totalPage > 0) {
            $page = 1;
            $offset = 0;
        }
        $data = $this->pembayaran->getData($limit,$offset,$search);
        $html = '';
        foreach ($data as $i => $row) {
            $number = $offset + $i + 1;
            $action = '';
                if ($role == 'admin') {
                    $action .= '
                        <button 
                            action="pembayaran"
                            data-id="'.$row['id_pembayaran'].'"
                            data-id_pelanggan="'.$row['id_pelanggan'].'"
                            data-nama="'.$row['nama'].'"
                            data-nama_paket="'.$row['nama_paket'].'"
                            data-invoice="'.$row['invoice'].'"
                            data-periode="'.$row['periode'].'"
                            data-total_tagihan="'.$row['total_tagihan'].'"
                            data-tanggal_tagihan="'.$row['tanggal_tagihan'].'"
                            style="--action-color: #08A14D;">
                            <i class="fa-solid fa-money-bill"></i>
                        </button>';
                    if ($row['status_pembayaran'] != 'lunas') {
                        $action .= '
                        <button
                            action="konfirmasi" 
                            class="btn-konfirmasi"
                            data-id="'.$row['id_pembayaran'].'"
                            data-url="'.base_url('dashboard/pembayaran/konfirmasi').'"
                            style="--action-color: #07E16D;">
                            <i class="fa-solid fa-check"></i>
                        </button>';
                    }
                    if ($row['status_pembayaran'] == 'lunas') {
                        $action .= '
                        <button 
                            action="print"
                            data-id="'.$row['id_pembayaran'].'"
                            style="--action-color: #fbd70e;">
                            <i class="fa-solid fa-print"></i>
                        </button>';
                    }
                    $action .= '
                        <button 
                            action="delete" 
                            class="btn-delete"
                            data-id="'.$row['id_pembayaran'].'"
                            data-url="'.base_url('dashboard/pembayaran/delete').'"
                            style="--action-color: #FD6666;">
                            <i class="fa-solid fa-trash"></i>
                        </button>';
                } elseif($role == 'billing'){
                    $action .= '
                        <button 
                            action="pembayaran"
                            data-id="'.$row['id_pembayaran'].'"
                            data-id_pelanggan="'.$row['id_pelanggan'].'"
                            data-invoice="'.$row['invoice'].'"
                            data-periode="'.$row['periode'].'"
                            data-total_tagihan="'.$row['total_tagihan'].'"
                            data-tanggal_tagihan="'.$row['tanggal_tagihan'].'"
                            style="--action-color: #08A14D;">
                            <i class="fa-solid fa-money-bill"></i>
                        </button>';
                    if ($row['status_pembayaran'] == 'lunas') {
                        $action .= '
                        <button 
                            action="print"
                            data-id="'.$row['id_pembayaran'].'"
                            style="--action-color: #fbd70e;">
                            <i class="fa-solid fa-print"></i>
                        </button>';
                    }
                }

            $html .= '
            <tr>
                <td>'.$number.'</td>
                <td>'.$row['invoice'].'</td>
                <td>'.$row['id_pelanggan'].'</td>
                <td>'.$row['nama'].'</td>
                <td>'.$row['nama_paket'].'</td>
                <td>'.$row['periode'].'</td>
                <td>Rp '.number_format($row['total_tagihan'], 0, ',', '.').'</td>
                <td>'.$row['metode_pembayaran'].'</td>
                <td>'.$row['status_pembayaran'].'</td>
                <td>'.
                    (!empty($row['tanggal_bayar']) 
                        ? date('d-m-Y H:i', strtotime($row['tanggal_bayar'])) 
                        : '-')
                .'</td>
                <td class="action-2">
                '.$action.'
                </td>
            </tr>';
        }

        if (empty($data)) {
            $html = '
            <tr>
                <td colspan="11" style="text-align:center;">Data tidak ditemukan</td>
            </tr>';
        }

        $start = $total ? $offset + 1 : 0;
        $end = $offset + count($data);
        if ($end > $total) {
            $end = $total;
        }
        $pagination = custom_pagination($page, $totalPage);
        $showing = "
            Showing {$start} to {$end} of {$total} entries
        ";
        return $this->response->setJSON([
            'html' => $html,
            'pagination' => $pagination,
            'showing'   => $showing
        ]);
    }

    public function getPelanggan()
    {
        $id = $this->request->getGet('id');

        $data = $this->pelanggan
            ->select('pelanggan.*, paket_layanan.nama_paket, paket_layanan.tarif')
            ->join('paket_layanan', 'paket_layanan.id_paket = pelanggan.paket_id')
            ->where('pelanggan.id_pelanggan', $id)
            ->first();

        if (!$data) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Pelanggan tidak ditemukan'
            ]);
        }

        return $this->response->setJSON(
            json_response(
            true, 'data ada', $data
            )
        );
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $metode = $this->request->getPost('metode');
        $file = $this->request->getFile('bukti');
        $namaFile = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads/bukti', $namaFile);
        }
        $status = ($metode == 'cash')
            ? 'lunas'
            : 'menunggu_verifikasi';
        $data = [
            'metode_pembayaran' => $metode,
            'bukti_pembayaran'  => $namaFile,
            'tanggal_bayar'     => $this->request->getPost('tanggal_bayar'),
            'total_bayar'       => $this->request->getPost('total_bayar'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'status_pembayaran' => $status
        ];
        $message = new Message_model();
        $this->pembayaran->update($id, $data);

        $data = $this->pembayaran
            ->select('pembayaran.*, pelanggan.nama')
            ->join('pelanggan', 'pelanggan.id_pelanggan = pembayaran.id_pelanggan')
            ->where('id_pembayaran', $id)
            ->first();

        $message->insert([
            'message' => 'Pelanggan ' . $data['nama'] . ' telah melakukan pembayaran',
            'is_read' => 0,
            'status' => 'unread'
        ]);
        return $this->response->setJSON(
            json_response('success', 'Pembayaran berhasil')
        );
    }
    public function konfirmasi()
    {
        $id = $this->request->getPost('id');
        $pembayaran = $this->pembayaran->find($id);
        if (!$pembayaran) {
            return $this->response->setJSON(
                json_response(
                    'error',
                    'Data pembayaran tidak ditemukan'
                )
            );
        }
        if (strtolower($pembayaran['status_pembayaran']) === 'pending') {
            return $this->response->setJSON(
                json_response(
                    'error',
                    'Pembayaran masih pending, tidak dapat dikonfirmasi'
                )
            );
        }
        $update = $this->pembayaran->update($id, [
            'status_pembayaran' => 'Lunas'
        ]);
        if ($update) {
            return $this->response->setJSON(
                json_response(
                    'success',
                    'Pembayaran berhasil dikonfirmasi'
                )
            );
        }

        return $this->response->setJSON(
            json_response(
                'error',
                'Pembayaran gagal dikonfirmasi'
            )
        );
    }
    public function print($id)
    {
       $data = $this->pembayaran
        ->select('pembayaran.*, pelanggan.id_pelanggan, pelanggan.nama, pelanggan.nomor_wa, paket_layanan.nama_paket, paket_layanan.tarif')
        ->join('pelanggan', 'pelanggan.id_pelanggan = pembayaran.id_pelanggan')
        ->join('paket_layanan', 'paket_layanan.id_paket = pelanggan.paket_id')
        ->where('pembayaran.id_pembayaran', $id)
        ->first();

        $data['kembalian'] = $data['total_bayar'] - $data['total_tagihan'];

        return view('component/pembayaran/print-form', [
            'pembayaran' => $data
        ]);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $pembayaran = $this->pembayaran->find($id);
        if (!$pembayaran) {
            return $this->response->setJSON(
                json_response('error', 'Data pembayaran tidak ditemukan')
            );
        }
        $res = $this->pembayaran->delete($id);

        if ($res) {
            return $this->response->setJSON(
                json_response('success', 'Data pembayaran berhasil dihapus')
            );
        }

        return $this->response->setJSON(
            json_response('error', 'Gagal menghapus data pembayaran')
        );
    }
}
