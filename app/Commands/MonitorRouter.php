<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use App\Service\MikrotikService;
use App\Repositories\Mikrotik\MikrotikRepo;

class MonitorRouter extends BaseCommand
{
    protected $group = 'Monitoring';
    protected $name = 'monitor:router';
    protected $description = 'Monitor router dan kirim WA otomatis';
    public function run(array $params)
    {
        $service = new MikrotikService(
            new MikrotikRepo()
        );
        $result = $service->detectServerDown();
        CLI::write(json_encode($result));
    }
}