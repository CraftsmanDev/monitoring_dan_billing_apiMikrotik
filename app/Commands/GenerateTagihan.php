<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Service\TagihanService;

class GenerateTagihan extends BaseCommand
{
    protected $group = 'Billing';
    protected $name = 'billing:generate';
    protected $description =
        'Generate tagihan internet otomatis';
    public function run(array $params)
    {
        $service = new TagihanService();
        $result = $service->tagihan();
        CLI::write(
            json_encode($result)
        );
    }
}