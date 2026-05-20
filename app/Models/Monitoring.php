<?php

namespace App\Models;

use CodeIgniter\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring_status';

    protected $allowedFields = [
        'device_name',
        'status',
        'last_notified'
    ];
}