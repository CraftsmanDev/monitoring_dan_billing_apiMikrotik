<?php

namespace App\Service;
use App\Models\Pelanggan_model;
use App\Models\Users_Model;
use App\Models\Pembayaran_Model;
use App\Service\WhatsappService;

class TagihanService
{
    public function tagihan()
    {
        $pelanggan = new Pelanggan_model();
        $pembayaran = new Pembayaran_Model();
        $petugas = new Users_Model();
        $wa = new WhatsappService();
        $allPelanggan = $pelanggan
            ->select('pelanggan.*, paket_layanan.tarif')
            ->join(
                'paket_layanan',
                'paket_layanan.id_paket = pelanggan.paket_id'
            )
            ->where('pelanggan.status', 'AKTIF')
            ->findAll();
        $petugas_billing = $petugas
        ->select('nomor_wa, nama')
        ->where('role', 'billing')
        ->first();
        $tanggal_tagihan = date('Y-m-d');
        $periode = date('Y-m');
        $jatuh_tempo = date('Y-m-10');
        $total = 0;
        foreach ($allPelanggan as $p) {
            if (date('Y-m') < date('Y-m', strtotime($p['tanggal_mulai_tagihan']))) {
                continue;
            }
            if (empty($p['tarif'])) {
                continue;
            }
            $cek = $pembayaran
                ->where(
                    'id_pelanggan',
                    $p['id_pelanggan']
                )
                ->where(
                    'periode',
                    $periode
                )
                ->countAllResults();
            if ($cek > 0) {
                continue;
            }
            $invoice ='INV-' .date('Ym') . '-' .rand(1000, 9999);
            $pembayaran->insert([
                'invoice' => $invoice,
                'id_pelanggan' => $p['id_pelanggan'],
                'tanggal_tagihan' => $tanggal_tagihan,
                'periode' => $periode,
                'jatuh_tempo' => $jatuh_tempo,
                'metode_pembayaran' => null,
                'bukti_pembayaran' => null,
                'total_tagihan' => $p['tarif'],
                'total_bayar' => 0,
                'status_pembayaran' => 'pending',
                'keterangan' => null
            ]);
            $nomor = $p['nomor_wa'];
             $nama_billing =
                $petugas_billing['nama'] ?? '-';
            $nomor_billing =
                $petugas_billing['nomor_wa'] ?? '-';
            $message =
                "INFORMASI TAGIHAN INTERNET AB NETWORK\n\n" .
                "Halo {$p['nama']},\n\n" .
                "Tagihan internet Anda untuk periode {$periode} telah tersedia.\n\n" .
                "Total Tagihan : Rp " .number_format($p['tarif'],0,',','.') . "\n" .
                "Jatuh Tempo : " . $jatuh_tempo . "\n\n" .
                "Pembayaran Transfer:\n" .
                "BANK BRI : 345723635478\n" .
                "Atas Nama : Arif\n\n" .
                "Pembayaran Cash:\n" .
                "{$nama_billing}\n" .
                "{$nomor_billing}\n\n".
                "Mohon melakukan pembayaran sebelum jatuh tempo agar layanan tetap aktif.\n\n" .
                "Terima kasih.";
            if (!empty($nomor)) {
                $wa->send(
                        $nomor,
                        $message
                    );
                sleep(
                    rand(2, 5)
                );
            }
            $total++;
        }
        return [
            'status' => true,
            'message' =>
                $total .
                ' tagihan berhasil dibuat'
        ];
    }
}