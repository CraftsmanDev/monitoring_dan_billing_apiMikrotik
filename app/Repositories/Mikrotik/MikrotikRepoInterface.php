<?php

namespace App\Repositories\Mikrotik;

interface MikrotikRepoInterface
{
    public function getProfiles(array $router): array;
    public function addProfile(array $router, string $name, string $rate): array;
    public function updateProfile(array $router, string $oldName, string $newName, string $rate): array;
    public function deleteProfile(array $router, string $name): array;

    public function getSecret(array $router): array;
    public function addSecret(array $router, string $name, string $profile, string $password): array;
    public function updateSecret(array $router, string $oldName, string $NewName, string $profile, string $password): array;
    public function deleteSecret(array $router, string $name): array;

    public function getInterfaceTraffic(array $router, string $interface): array;
    public function getSystemResource(array $router): array;
    public function pingHost(array $router, string $host): array;
    public function getLog(array $router): array;

    public function getActive(array $router): array;
}