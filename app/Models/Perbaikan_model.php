<?php

namespace App\Models;

use CodeIgniter\Model;

class Perbaikan_model extends Model
{
    protected $table = 'perbaikan';
    protected $primaryKey = 'id_perbaikan';
    protected $allowedFields = [
        'id_pelanggan',
        'kategori',
        'status',
        'tanggal',
        'anggaran',
        'keterangan'
    ];

    public function getData(int $limit, int $offset, ?string $search = null, ?string $bulan = null, ?string $tahun = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('perbaikan.*, pelanggan.nama');
        $builder->join('pelanggan', 'pelanggan.id_pelanggan = perbaikan.id_pelanggan');
        if ($search) {
            $builder->groupStart()
                ->like('perbaikan.id_pelanggan', $search)
                ->orLike('perbaikan.kategori', $search)
                ->orLike('pelanggan.nama', $search)
                ->groupEnd();
        }
        if ($bulan) {
            $builder->where(
                'MONTH(perbaikan.tanggal) =',
                (int)$bulan
            );
        }

        if ($tahun) {
            $builder->where(
                'YEAR(perbaikan.tanggal) =',
                (int)$tahun
            );
        }
        return $builder
            ->orderBy('perbaikan.id_perbaikan', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    public function countData(?string $search = null)
    {
        $builder = $this->db->table($this->table);
        $builder->join('pelanggan', 'pelanggan.id_pelanggan = perbaikan.id_pelanggan');
        if ($search) {
            $builder->groupStart()
                ->like('perbaikan.id_pelanggan', $search)
                ->orLike('perbaikan.kategori', $search)
                ->orLike('pelanggan.nama', $search)
                ->groupEnd();
        }
        return $builder->countAllResults();
    }
}