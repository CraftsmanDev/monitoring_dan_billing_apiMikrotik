<?php

namespace App\Models;

use CodeIgniter\Model;

class Message_model extends Model
{
    protected $table = 'message';
    protected $primaryKey = 'id_message';
    protected $allowedFields = [
        'message',
        'is_read',
        'status',
        'id_users',
        'created_at'
    ];
}