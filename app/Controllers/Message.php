<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Message_model;

class Message extends BaseController
{
    public function getmessage()
    {
        $message = new Message_model();

        $data = $message
            ->where('id_users !=', session()->get('id_users'))
            ->orderBy('id_message', 'DESC')
            ->findAll();

        $total = $message
            ->where('id_users !=', session()->get('id_users'))
            ->where('is_read', 0)
            ->countAllResults();

        return $this->response->setJSON([
            'data' => $data,
            'total' => $total
        ]);
    }
    public function readAll()
    {
        $message = new Message_model();

        $message
            ->where('is_read', 0)
            ->set([
                'is_read' => 1,
                'status'  => 'read'
            ])
            ->update();

        return $this->response
            ->setJSON([
                'status' => 'success'
            ]);
    }
}