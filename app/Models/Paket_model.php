<?php

namespace App\Models;

use CodeIgniter\Model;

class Paket_model extends Model
{
    protected $table = 'paket_layanan';

    protected $primaryKey = 'id_paket';

    protected $allowedFields = [
        'nama_paket',
        'kecepatan',
        'tarif'
    ];
    public function getData(int $limit, int $offset, ?string $search = null) {
        $builder = $this->builder();
        if ($search) {
            $builder->groupStart();
            $builder->like(
                'nama_paket',
                $search
            );
            $builder->orLike(
                'kecepatan',
                $search
            );
            $builder->orLike(
                'tarif',
                $search
            );
            $builder->groupEnd();
        }
        return $builder
            ->orderBy('id_paket', 'DESC')
            ->get($limit, $offset)
            ->getResultArray();
    }

    public function countData(?string $search = null) {
        $builder = $this->builder();
        if ($search) {
            $builder->groupStart();
            $builder->like(
                'nama_paket',
                $search
            );
            $builder->orLike(
                'kecepatan',
                $search
            );
            $builder->orLike(
                'tarif',
                $search
            );
            $builder->groupEnd();
        }
        return $builder->countAllResults();
    }
}