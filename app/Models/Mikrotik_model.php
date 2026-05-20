<?php
namespace App\Models;
use CodeIgniter\Model;

class Mikrotik_model extends Model
{
    protected $table = 'mikrotik';
    protected $primaryKey = 'id_mikrotik';
    protected $fillable = [
        'name',
        'ip_address',
        'username',
        'password',
        'port',
        'status',
    ];

    public function getActiveRouters()
    {
        return $this->where('status', 'enable')->findAll();
    }
}