<?php

namespace App\Controllers;
use App\Models\Users_Model;

class Petugas extends BaseController
{
    protected $model;
    public function __Construct()
    {
        $this->model = new Users_Model();
    }

    public function index()
    {
        $data = [
            'title' => 'Petugas'
        ];
        return view('component/petugas/index', $data);
    }

    public function fetch()
    {
        $data = $this->model->findAll();
        $html = '';
        foreach ($data as $i => $row) {
            $html .= '
                <tr>
                    <td>'.($i + 1).'</td>
                    <td>'.$row['username'].'</td>
                    <td>'.$row['nama'].'</td>
                    <td>'.$row['nomor_wa'].'</td>
                    <td>'.$row['role'].'</td>
                    <td class="action">
                        <button 
                        action="edit" 
                        style="--action-color: #0e75fb;"
                        data-id="'.$row['id_users'].'"
                        data-username="'.$row['username'].'"
                        data-nama="'.$row['nama'].'"
                        data-nomor_wa="'.$row['nomor_wa'].'"
                        data-role="'.$row['role'].'"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button action="delete" 
                        style="--action-color: #FD6666;" 
                        data-id="'.$row['id_users'].'"
                        data-url="'.base_url('dashboard/petugas/delete').'">
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

        return $this->response->setJSON([
            'html' => $html
        ]);
    }

    public function store()
    {
        $username  = $this->request->getPost('username');
        $nama      = $this->request->getPost('nama');
        $nomor_wa  = $this->request->getPost('nomor_wa');
        $password  = $this->request->getPost('password');
        $role      = $this->request->getPost('role');

        if (!$username || !$nama || !$password || !$role) {
            return $this->response->setJSON(
                json_response('error', 'Data tidak boleh kosong')
            );
        }

        $data = [
            'username'  => $username,
            'nama'      => $nama,
            'nomor_wa'  => $nomor_wa,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'role'      => $role
        ];

        $insert = $this->model->insert($data);

        if ($insert) {
            return $this->response->setJSON(
                json_response('success', 'Petugas berhasil ditambahkan')
            );
        }

        return $this->response->setJSON(
            json_response('error', 'Gagal menambahkan petugas')
        );
    }

    public function update()
    {
        $id        = $this->request->getPost('id');
        $username  = $this->request->getPost('username');
        $nama      = $this->request->getPost('nama');
        $nomor_wa  = $this->request->getPost('nomor_wa');
        $role      = $this->request->getPost('role');
        $petugas = $this->model->find($id);
        if (!$petugas) {
            return $this->response->setJSON(
                json_response('error', 'Data petugas tidak ditemukan')
            );
        }
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $data = [
            'username' => $username,
            'nama'     => $nama,
            'nomor_wa' => $nomor_wa,
            'role'     => $role
        ];
        $update = $this->model->update($id, $data);
        if ($update) {
            return $this->response->setJSON(
                json_response('success', 'Petugas berhasil diupdate')
            );
        }
        return $this->response->setJSON(
            json_response('error', 'Gagal update petugas')
        );
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $petugas = $this->model->find($id);
        if (!$petugas) {
            return $this->response->setJSON(
                json_response('error', 'Data petugas tidak ditemukan')
            );
        }
        $delete = $this->model->delete($id);
        if ($delete) {
            return $this->response->setJSON(
                json_response('success', 'Petugas berhasil dihapus')
            );
        }
        return $this->response->setJSON(
            json_response('error', 'Gagal menghapus petugas')
        );
    }
}
