<?php

namespace App\Controllers;

use App\Models\Users_Model;

class Login extends BaseController
{
    public function index()
    {
        if (session()->get('islogin')) {
            return redirect()->to(('dashboard'));
        }

        $data = [
            "title" => "login"
        ];
        return view('Login', $data);
    }

    public function store()
    {
        $session = session();

        $model = new Users_Model();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (verify_password($password, $user['password'])) {
                $session->set([
                    'id_users' => $user['id_users'],
                    'nama'     => $user['nama'],
                    'username' => $user['username'],
                    'foto'     => $user['foto'],
                    'role'     => $user['role'],
                    'islogin'  => true
                ]);
                return redirect()->to(base_url('dashboard'));
            } else {
                return redirect()->back()->with('error', 'password salah');
            }
        } else {
            return redirect()->back()->with('error', 'username tidak ditemukan');
        }
    }

    public function logout()
    {
        session()->remove('islogin');
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
