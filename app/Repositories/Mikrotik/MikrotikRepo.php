<?php

namespace App\Repositories\Mikrotik;

use App\Libraries\MikrotikClient;

class MikrotikRepo implements MikrotikRepoInterface
{
    private MikrotikClient $client;

    public function __construct()
    {
        $this->client = new MikrotikClient();
    }

    private function conn(array $router): MikrotikClient
    {
        return $this->client->connect($router);
    }

    // PROFILE 
    public function getProfiles(array $router): array
    {
        return $this->conn($router)->execute('/ppp/profile/print');
    }

    public function addProfile(array $router, string $name, string $rate): array
    {
        $res = $this->getProfiles($router);
        return $this->conn($router)->execute('/ppp/profile/add', [
            'name' => $name,
            'rate-limit' => $rate
        ]);
    }

    public function updateProfile(array $router, string $oldName, string $newName, string $rate): array
    {
        $res = $this->getProfiles($router);

        if (!$res['status']) return $res;

        $id = null;

        foreach ($res['data'] as $p) {
            if (($p['name'] ?? '') === $oldName) {
                $id = $p['.id'] ?? null;
                break;
            }
        }

        if (!$id) {
            return [
                'status' => false,
                'message' => 'Profile tidak ditemukan',
                'data' => []
            ];
        }

        return $this->conn($router)->execute('/ppp/profile/set', [
            '.id' => $id,
            'name' => $newName,
            'rate-limit' => $rate
        ]);
    }

    public function deleteProfile(array $router, string $name): array
    {
        $res = $this->getProfiles($router);

        if (!$res['status']) return $res;

        $id = null;

        foreach ($res['data'] as $p) {
            if (($p['name'] ?? '') === $name) {
                $id = $p['.id'] ?? null;
                break;
            }
        }

        if (!$id) {
            return [
                'status' => false,
                'message' => 'Profile tidak ditemukan',
                'data' => []
            ];
        }

        return $this->conn($router)->execute('/ppp/profile/remove', [
            '.id' => $id
        ]);
    }

    public function getSecret(array $router): array
    {
        return $this->conn($router)
            ->execute('/ppp/secret/print');
    }

    public function addSecret(array $router, string $name, string $profile, string $password): array
    {
        return $this->conn($router)->execute('/ppp/secret/add', [
            'name'     => $name,
            'password' => $password,
            'profile'  => $profile
        ]);
    }

    public function updateSecret(array $router,string $oldName,string $newName,string $profile,string $password): array
    {
        $res = $this->getSecret($router);

        if (!$res['status']) {
            return $res;
        }

        $id = null;

        foreach ($res['data'] as $s) {

            if (($s['name'] ?? '') == $oldName) {

                $id = $s['.id'] ?? null;
                break;
            }
        }

        if (!$id) {

            return [
                'status' => false,
                'message' => 'Secret tidak ditemukan',
                'data' => []
            ];
        }

        return $this->conn($router)->execute('/ppp/secret/set', [
            '.id'      => $id,
            'name'     => $newName,
            'profile'  => $profile,
            'password' => $password,
        ]);
    }

    public function deleteSecret(array $router, string $name): array
    {
        $res = $this->getSecret($router);

        if (!$res['status']) return $res;

        $id = null;

        foreach ($res['data'] as $s) {
            if (($s['name'] ?? '') === $name) {
                $id = $s['.id'] ?? null;
                break;
            }
        }

        if (!$id) {
            return [
                'status' => false,
                'message' => 'Secret tidak ditemukan',
                'data' => []
            ];
        }

        return $this->conn($router)->execute('/ppp/secret/remove', [
            '.id' => $id
        ]);
    }
    
    // TRAFFIC INTERFACE
    public function getInterfaceTraffic(array $router, string $interface): array
    {
        return $this->conn($router)->execute(
            '/interface/monitor-traffic',
            [
                'interface' => $interface,
                'once' => 'true'
            ]
        );
    }

    public function getSystemResource(array $router): array
    {
         try {

            $result = $this->conn($router)->execute(
                '/system/resource/print'
            );

            return [
                'status' => true,
                'data'   => $result
            ];

        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());

            return [
                'status'  => false,
                'message' => $e->getMessage(),
                'data'    => []
            ];
        }
    }

    public function pingHost(array $router, string $host): array
    {
        return $this->conn($router)->execute(
            '/ping',
            [
                'address' => $host,
                'count' => '4'
            ]
        );
    }

    public function getLog(array $router): array
    {
        return $this->conn($router)->execute(
            '/log/print'
        );
    }

    // ACTIVE
    public function getActive(array $router): array
    {
        return $this->conn($router)->execute('/ppp/active/print');
    }
}