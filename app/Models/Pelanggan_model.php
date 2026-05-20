<?php

namespace App\Models;

use CodeIgniter\Model;

class Pelanggan_model extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['nama','id_pelanggan', 'area', 'nomor_wa', 'paket_id', 'tanggal_register', 'tanggal_mulai_tagihan', 'status', 'tanggal_berhenti'];
    public function getData(int $limit, int $offset, ?string $search = null) {
        $builder = $this->builder();
        if ($search) {
            $builder->groupStart();
            $builder->like('id_pelanggan',$search);
            $builder->orLike('nama', $search);
            $builder->groupEnd();
        }
        return $builder
            ->select('pelanggan.*, paket_layanan.nama_paket, paket_layanan.tarif')
            ->join('paket_layanan', 'paket_layanan.id_paket = pelanggan.paket_id', 'left')
            ->orderBy('id_pelanggan', 'DESC')
            ->get($limit, $offset)
            ->getResultArray();
    }

    public function countData(?string $search = null) {
        $builder = $this->builder();
        if ($search) {
            $builder->groupStart();
            $builder->like('id_pelanggan',$search);
            $builder->orLike('nama', $search);
            $builder->groupEnd();
        }
        return $builder->countAllResults();
    }
}
