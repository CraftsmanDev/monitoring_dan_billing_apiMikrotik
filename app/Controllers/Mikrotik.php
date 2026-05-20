<?php

namespace App\Controllers;
use App\Models\Mikrotik_model;

class Mikrotik extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new Mikrotik_model();
    }

    public function index() 
    {
        $data = [
            'title' => 'Mikrotik Routers',
        ];

        return view('component/mikrotik/routers', $data);
    }

    public function fetch()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = 5;
        $client = new \App\Libraries\MikrotikClient();
        $offset = ($page - 1) * $limit;
        $total = $this->model->countAll();
        $totalPage = ceil($total / $limit);
        $data = $this->model
        ->orderBy('id_mikrotik', 'DESC')
        ->limit($limit, $offset)
        ->find();
        $html = '';
        foreach ($data as $i => &$row) {
            $test = $client->testConnection($row);
            $status = $test['status']
                ? '<span style="color:green">CONNECTED</span>'
                : '<span style="color:red">DISCONNECTED</span>';
            $html .= '
                <tr>
                    <td>'.($i + 1).'</td>
                    <td>'.$row['ip_address'].'</td>
                    <td>'.$row['username'].'</td>
                    <td>'.$row['port'].'</td>
                    <td>'.$status.'</td>
                    <td class="action-2">
                       <button 
                            action="edit"
                            style="--action-color: #0e75fb;"
                            data-id="'.$row['id_mikrotik'].'"
                            data-address="'.$row['ip_address'].'"
                            data-username="'.$row['username'].'"
                            data-password="'.$row['password'].'"
                            data-port="'.$row['port'].'">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button 
                            action="delete"
                            style="--action-color: #FD6666;"
                            data-id="'.$row['id_mikrotik'].'"
                            data-url="'.base_url('dashboard/mikrotik/delete').'">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            ';
        }

        $start = $offset + 1;
        $end = $offset + count($data);
        if ($total == 0) {
            $start = 0;
            $end = 0;
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

    public function test()
    {
        $router = [
            'ip_address' => $this->request->getPost('address'),
            'username'   => $this->request->getPost('username'),
            'password'   => $this->request->getPost('password'),
            'port'       => $this->request->getPost('port'),
        ];

        $mikrotik = new MikrotikService();

        $result = $mikrotik->safeQuery(
            $router,
            '/system/identity/print'
        );

        if (isset($result['error'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $result['message'],
                'token'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Connected : ' . ($result[0]['name'] ?? 'MikroTik'),
            'token'   => csrf_hash()
        ]);
    }

    public function store()
    {
        $ip_address = $this->request->getPost('address');
        $username   = $this->request->getPost('username');
        $password   = $this->request->getPost('password');
        $port       = $this->request->getPost('port');

        if (
            empty($ip_address) ||
            empty($username) ||
            empty($password) ||
            empty($port)
        ) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Semua field wajib diisi',
                'token'   => csrf_hash()
            ]);
        }

        $router = [
            'ip_address' => $ip_address,
            'username'   => $username,
            'password'   => $password,
            'port'       => $port,
        ];

        $mikrotik = new MikrotikService();

        $result = $mikrotik->safeQuery(
            $router,
            '/system/identity/print'
        );

        if (isset($result['error'])) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $result['message'],
                'token'   => csrf_hash()
            ]);
        }

        // simpan database
        $this->model->save([
            'ip_address' => $ip_address,
            'username'   => $username,
            'password'   => $password,
            'port'       => $port,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Router MikroTik berhasil ditambahkan',
            'token'   => csrf_hash()
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $ip_address = $this->request->getPost('address');
        $username   = $this->request->getPost('username');
        $password   = $this->request->getPost('password');
        $port       = $this->request->getPost('port');

        // validasi
        if (
            empty($ip_address) ||
            empty($username) ||
            empty($password) ||
            empty($port)
        ) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Semua field wajib diisi',
                'token'   => csrf_hash()
            ]);
        }

        $router = [
            'ip_address' => $ip_address,
            'username'   => $username,
            'password'   => $password,
            'port'       => $port,
        ];

        $mikrotik = new MikrotikService();

        // test koneksi
        $result = $mikrotik->safeQuery(
            $router,
            '/system/identity/print'
        );

        // gagal koneksi
        if (isset($result['error'])) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $result['message'],
                'token'   => csrf_hash()
            ]);
        }

        // update database
        $this->model->update($id, [
            'ip_address' => $ip_address,
            'username'   => $username,
            'password'   => $password,
            'port'       => $port,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Router MikroTik berhasil diupdate',
            'token'   => csrf_hash()
        ]);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        // cek data
        $router = $this->model->find($id);

        if (!$router) {

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data router tidak ditemukan',
                'token'   => csrf_hash()
            ]);
        }

        // hapus data
        $this->model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Router MikroTik berhasil dihapus',
            'token'   => csrf_hash()
        ]);
    }
}