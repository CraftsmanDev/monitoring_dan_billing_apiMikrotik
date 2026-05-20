<?php 

namespace App\Controllers;

use App\Models\Users_Model;

class Profil extends BaseController
{
    public function update()
    {
        $users = new Users_Model();

        $id       = $this->request->getPost('id');
        $username = $this->request->getPost('username');
        $nama     = $this->request->getPost('nama');
        $foto = $this->request->getFile('foto');
        $password = $this->request->getPost('password');
        $confPass = $this->request->getPost('confpassword');

        $data = [
            'username' => $username,
            'nama'     => $nama,
        ];

        if (!empty($password)) {
            if ($password != $confPass) {
                return $this->response->setJSON(
                    json_response('error', 'Password dan confirm password tidak cocok')
                );
            }

            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move('assest/', $newName);
            $data['foto'] = $newName;
        }
        $update = $users->update($id, $data);

        if ($update) {
            session()->set([
                'username' => $username,
                'nama'     => $nama,
            ]);

            if (isset($data['foto'])) {
                session()->set('foto', $data['foto']);
            }
            return $this->response->setJSON(
                json_response('success', 'Profil berhasil diupdate')
            );
        }

        return $this->response->setJSON(
            json_response('error', 'Profil gagal diupdate')
        );
    }
}
?>