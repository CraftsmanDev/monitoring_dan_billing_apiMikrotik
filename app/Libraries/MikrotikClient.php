<?php

namespace App\Libraries;

use RouterOS\Client;
use RouterOS\Query;

class MikrotikClient
{
    private ?Client $client = null;

    public function connect(array $router): self
    {
        $this->client = new Client([
            'host'    => $router['ip_address'],
            'user'    => $router['username'],
            'pass'    => $router['password'],
            'port'    => (int) $router['port'],
            'timeout' => 3
        ]);

        return $this;
    }

    public function execute(string $endpoint, array $params = []): array
    {
        try {
            $query = new Query($endpoint);

            foreach ($params as $k => $v) {
                $query->equal($k, $v);
            }

            $result = $this->client->query($query)->read();

            return [
                'status' => true,
                'message' => 'success',
                'data' => $result
            ];

        } catch (\Throwable $e) {

            log_message('error', 'MikrotikClient: ' . $e->getMessage());

            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
    public function testConnection(array $router): array
    {
        try {
            $this->connect($router);

            // test ringan (ambil identity router)
            $query = new \RouterOS\Query('/system/identity/print');

            $result = $this->client->query($query)->read();

            return [
                'status' => true,
                'message' => 'connected',
                'data' => $result
            ];

        } catch (\Throwable $e) {

            log_message('error', 'Mikrotik connect failed: ' . $e->getMessage());

            return [
                'status' => false,
                'message' => 'disconnected',
                'data' => []
            ];
        }
    }
}