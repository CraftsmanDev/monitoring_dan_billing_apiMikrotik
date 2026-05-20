<?php

namespace App\Models;

use CodeIgniter\Model;

class Pembayaran_Model extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $allowedFields = [

        'invoice',
        'id_pelanggan',
        'periode',
        'tanggal_tagihan',
        'jatuh_tempo',
        'metode_pembayaran',
        'bukti_pembayaran',
        'total_tagihan',
        'total_bayar',
        'status_pembayaran',
        'tanggal_bayar',
        'keterangan',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    private function queryData(?string $search = null, ?string $bulan = null,?string $tahun = null) {
        $builder = $this->builder();
        $builder->select('
            pembayaran.*,
            pelanggan.nama,
            pelanggan.nomor_wa,
            paket_layanan.nama_paket
        ');

        $builder->join(
            'pelanggan',
            'pelanggan.id_pelanggan = pembayaran.id_pelanggan'
        );

        $builder->join(
            'paket_layanan',
            'paket_layanan.id_paket = pelanggan.paket_id'
        );

        if ($search) {
            $builder->groupStart();
            $builder->like(
                'pelanggan.id_pelanggan',
                $search
            );
            $builder->orLike(
                'pembayaran.invoice',
                $search
            );
            $builder->orLike(
                'pelanggan.nama',
                $search
            );
            $builder->groupEnd();
        }
        if ($bulan) {
            $builder->where(
                'MONTH(pembayaran.tanggal_tagihan) =',
                (int)$bulan
            );
        }

        if ($tahun) {
            $builder->where(
                'YEAR(pembayaran.tanggal_tagihan) =',
                (int)$tahun
            );
        }
        return $builder;
    }

    public function getData(
        int $limit,
        int $offset,
        ?string $search = null,  ?string $bulan = null,?string $tahun = null) {
        return $this->queryData($search, $bulan, $tahun)
            ->orderBy(
                'pembayaran.id_pembayaran',
                'DESC'
            )
            ->get($limit, $offset)
            ->getResultArray();
    }

    public function countData(?string $search = null, ?string $bulan = null,?string $tahun = null) {

        return $this->queryData($search)
            ->countAllResults();
    }
}