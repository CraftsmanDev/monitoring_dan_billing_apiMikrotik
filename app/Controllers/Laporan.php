<?php

namespace App\Controllers;
use App\Models\Perbaikan_model;
use App\Models\Pembayaran_Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Laporan extends BaseController
{
    protected $perbaikan;
    protected $pembayaran;
    public function __Construct()
    {
        $this->perbaikan = new Perbaikan_model;
        $this->pembayaran = new Pembayaran_Model;
    }
    public function keuangan()
    {
        $data = [
            'title' => 'Laporan Keuangan'
        ];

        return view('component/laporan/laporan-keuangan', $data);
    }

    public function keuanganfetch()
        {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 10);
        $search = $this->request->getGet('search');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $offset = ($page - 1) * $limit;
        $total = $this->pembayaran->countData($search, $bulan, $tahun);
        $totalPage = ceil($total / $limit);
        if ($page > $totalPage && $totalPage > 0) {
            $page = 1;
            $offset = 0;
        }
        $data = $this->pembayaran->getData($limit,$offset,$search,$bulan,$tahun);
        $html = '';
        foreach ($data as $i => $row) {
            $number = $offset + $i + 1;
            $html .= '
            <tr>
                <td>'.$number.'</td>
                <td>'.$row['invoice'].'</td>
                <td>'.$row['id_pelanggan'].'</td>
                <td>'.$row['nama'].'</td>
                <td>'.$row['nama_paket'].'</td>
                <td>'.$row['periode'].'</td>
                <td>Rp '.number_format($row['total_tagihan'], 0, ',', '.').'</td>
                <td>'.$row['metode_pembayaran'].'</td>
                <td>'.$row['status_pembayaran'].'</td>
                <td>'.
                    (!empty($row['tanggal_bayar']) 
                        ? date('d-m-Y', strtotime($row['tanggal_bayar'])) 
                        : '-')
                .'</td>
            </tr>';
        }

        if (empty($data)) {
            $html = '
            <tr>
                <td colspan="11" style="text-align:center;">Data tidak ditemukan</td>
            </tr>';
        }

        $start = $total ? $offset + 1 : 0;
        $end = $offset + count($data);
        if ($end > $total) {
            $end = $total;
        }
        $pagination = custom_pagination($page, $totalPage);
        $showing = "
            Showing {$start} to {$end} of {$total} entries
        ";
        return $this->response->setJSON([
            'html' => $html,
            'pagination' => $pagination,
            'showing'   => $showing
        ]);
    }

    public function perbaikanfetch()
    {
        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = (int) ($this->request->getGet('limit') ?? 10);
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $search = $this->request->getGet('search');
        $offset = ($page - 1) * $limit;
        $total = $this->perbaikan->countData($search, $bulan, $tahun);
        $totalPage = ceil($total / $limit);
        if ($page > $totalPage && $totalPage > 0) {
            $page = 1;
            $offset = 0;
        }
        $data = $this->perbaikan->getData(
            $limit,
            $offset,
            $search,
            $bulan,
            $tahun
        );

        $html = '';
        foreach ($data as $i => $row) {
            $number = $offset + $i + 1;
            $html .= '
                <tr>
                    <td>'.$number.'</td>
                    <td>'.$row['id_pelanggan'].'</td>
                    <td>'.$row['nama'].'</td>
                    <td>'.$row['kategori'].'</td>
                    <td>'.$row['anggaran'].'</td>
                    <td>'.date('d-m-Y', strtotime($row['tanggal'])).'</td>
                    <td>'.$row['status'].'</td>
                    <td>'.$row['keterangan'].'</td>
                </tr>
            ';
        }

        if (empty($data)) {
            $html = '
                <tr>
                    <td colspan="8"style="text-align: center;">Data tidak ditemukan</td>
                </tr>
            ';
        }

        $start = $total ? $offset + 1 : 0;
        $end = $offset + count($data);
        if ($end > $total) {
            $end = $total;
        }
        $pagination = custom_pagination(
            $page,
            $totalPage
        );
        $showing = "
            Showing {$start} to {$end} of {$total} entries
        ";
        return $this->response->setJSON([
            'html' => $html,
            'pagination' => $pagination,
            'showing'   => $showing
        ]);
    }

    public function perbaikan()
    {
        $data = [
            'title' => 'Laporan Perbaikan'
        ];
        return view('component/laporan/laporan-perbaikan', $data);
    }
    public function exportPerbaikan()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $model = new Perbaikan_model();
        $builder = $model
            ->select('perbaikan.*, pelanggan.nama')
            ->join(
                'pelanggan',
                'pelanggan.id_pelanggan = perbaikan.id_pelanggan'
            );
        if ($bulan) {
            $builder->where(
                'MONTH(perbaikan.tanggal)',
                (int)$bulan
            );
        }
        if ($tahun) {
            $builder->where(
                'YEAR(perbaikan.tanggal)',
                (int)$tahun
            );
        }
        $data = $builder
            ->orderBy(
                'perbaikan.tanggal',
                'DESC'
            )
            ->findAll();
        $periode =
            ($bulan && $tahun)
            ? $bulan . '-' . $tahun
            : ($tahun
                ? $tahun
                : 'Semua Periode');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue(
            'A1',
            'LAPORAN PERBAIKAN AB NETWORK'
        );

        $sheet->setCellValue(
            'A2',
            'Periode : ' . $periode
        );

        $sheet->getStyle('A1:H2')
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('A1:H2')
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );

        $sheet->getStyle('A1:H2')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1')
            ->getFont()
            ->setSize(16);

        $sheet->getStyle('A2')
            ->getFont()
            ->setSize(12);

        $sheet->getRowDimension(1)
            ->setRowHeight(30);

        $sheet->getRowDimension(2)
            ->setRowHeight(22);
        $headerRow = 5;

        $sheet->setCellValue('A'.$headerRow, 'No');
        $sheet->setCellValue('B'.$headerRow, 'ID Pelanggan');
        $sheet->setCellValue('C'.$headerRow, 'Nama');
        $sheet->setCellValue('D'.$headerRow, 'Kategori');
        $sheet->setCellValue('E'.$headerRow, 'Anggaran');
        $sheet->setCellValue('F'.$headerRow, 'Tanggal');
        $sheet->setCellValue('G'.$headerRow, 'Status');
        $sheet->setCellValue('H'.$headerRow, 'Keterangan');
        $sheet->getStyle('A5:H5')
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' =>
                        \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '1F4E78'
                    ]
                ],
                'alignment' => [
                    'horizontal' =>
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' =>
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ]);

        $sheet->getRowDimension(5)
            ->setRowHeight(25);
        $row = 6;
        foreach ($data as $i => $d) {
            $sheet->setCellValue(
                'A'.$row,
                $i + 1
            );
            $sheet->setCellValue(
                'B'.$row,
                $d['id_pelanggan']
            );
            $sheet->setCellValue(
                'C'.$row,
                $d['nama']
            );
            $sheet->setCellValue(
                'D'.$row,
                $d['kategori']
            );
            $sheet->setCellValue(
                'E'.$row,
                $d['anggaran']
            );
            $sheet->setCellValue(
                'F'.$row,
                date(
                    'd-m-Y',
                    strtotime($d['tanggal'])
                )
            );
            $sheet->setCellValue(
                'G'.$row,
                $d['status']
            );
            $sheet->setCellValue(
                'H'.$row,
                $d['keterangan']
            );
            $row++;
        }
        $sheet->getStyle('E6:E'.($row - 1))
            ->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getStyle('A5:H'.($row - 1))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ]);
        $sheet->getStyle('A5:H'.($row - 1))
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );

        $sheet->getStyle('A5:B'.($row - 1))
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('F5:G'.($row - 1))
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );
        foreach (range('A', 'H') as $column) {

            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }
        $filename =
            'laporan-perbaikan-' .
            date('YmdHis') .
            '.xlsx';
        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        header(
            'Content-Disposition: attachment; filename="'.$filename.'"'
        );
        header('Cache-Control: max-age=0');
        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );
        $writer->save('php://output');
        exit;
    }

    public function exportKeuangan()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $builder = $this->pembayaran
            ->select('
                pembayaran.*,
                pelanggan.nama,
                paket_layanan.nama_paket
            ')
            ->join(
                'pelanggan',
                'pelanggan.id_pelanggan = pembayaran.id_pelanggan'
            )
            ->join(
                'paket_layanan',
                'paket_layanan.id_paket = pelanggan.paket_id'
            );

        if ($bulan) {

            $builder->where(
                'MONTH(pembayaran.tanggal_tagihan)',
                (int)$bulan
            );
        }

        if ($tahun) {

            $builder->where(
                'YEAR(pembayaran.tanggal_tagihan)',
                (int)$tahun
            );
        }

        $data = $builder
            ->orderBy(
                'pembayaran.id_pembayaran',
                'DESC'
            )
            ->findAll();

        $periode =
            ($bulan && $tahun)
            ? $bulan . '-' . $tahun
            : ($tahun
                ? $tahun
                : 'Semua Periode');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /*
        |--------------------------------------------------------------------------
        | JUDUL LAPORAN
        |--------------------------------------------------------------------------
        */
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');

        $sheet->setCellValue(
            'A1',
            'LAPORAN KEUANGAN AB NETWORK'
        );

        $sheet->setCellValue(
            'A2',
            'Periode : ' . $periode
        );

        $sheet->getStyle('A1:J2')
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('A1:J2')
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );

        $sheet->getStyle('A1:J2')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1')
            ->getFont()
            ->setSize(16);

        $sheet->getStyle('A2')
            ->getFont()
            ->setSize(12);

        $sheet->getRowDimension(1)
            ->setRowHeight(30);

        $sheet->getRowDimension(2)
            ->setRowHeight(22);

        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        */
        $headerRow = 5;

        $sheet->setCellValue('A'.$headerRow, 'No');
        $sheet->setCellValue('B'.$headerRow, 'Invoice');
        $sheet->setCellValue('C'.$headerRow, 'ID Pelanggan');
        $sheet->setCellValue('D'.$headerRow, 'Nama');
        $sheet->setCellValue('E'.$headerRow, 'Paket');
        $sheet->setCellValue('F'.$headerRow, 'Periode');
        $sheet->setCellValue('G'.$headerRow, 'Total Tagihan');
        $sheet->setCellValue('H'.$headerRow, 'Metode');
        $sheet->setCellValue('I'.$headerRow, 'Status');
        $sheet->setCellValue('J'.$headerRow, 'Tanggal Bayar');

        /*
        |--------------------------------------------------------------------------
        | STYLE HEADER
        |--------------------------------------------------------------------------
        */
        $sheet->getStyle('A5:J5')
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '1F4E78'
                    ]
                ],
                'alignment' => [
                    'horizontal' =>
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' =>
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ]);

        $sheet->getRowDimension(5)
            ->setRowHeight(25);

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */
        $row = 6;

        foreach ($data as $i => $d) {

            $sheet->setCellValue(
                'A'.$row,
                $i + 1
            );

            $sheet->setCellValue(
                'B'.$row,
                $d['invoice']
            );
            $sheet->setCellValue(
                'C'.$row,
                $d['id_pelanggan']
            );
            $sheet->setCellValue(
                'D'.$row,
                $d['nama']
            );
            $sheet->setCellValue(
                'E'.$row,
                $d['nama_paket']
            );
            $sheet->setCellValue(
                'F'.$row,
                $d['periode']
            );
            $sheet->setCellValue(
                'G'.$row,
                $d['total_tagihan']
            );
            $sheet->setCellValue(
                'H'.$row,
                $d['metode_pembayaran']
            );
            $sheet->setCellValue(
                'I'.$row,
                $d['status_pembayaran']
            );
            $sheet->setCellValue(
                'J'.$row,
                !empty($d['tanggal_bayar'])
                    ? date(
                        'd-m-Y',
                        strtotime($d['tanggal_bayar'])
                    )
                    : '-'
            );

            $row++;
        }
        $sheet->getStyle('G6:G'.($row - 1))
            ->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getStyle('A5:J'.($row - 1))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ]
                ]
            ]);
        $sheet->getStyle('A5:J'.($row - 1))
            ->getAlignment()
            ->setVertical(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            );
        $sheet->getStyle('A5:C'.($row - 1))
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('F5:J'.($row - 1))
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );
        foreach (range('A', 'J') as $column) {

            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }
        $filename =
            'laporan-keuangan-' .
            date('YmdHis') .
            '.xlsx';

        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        header(
            'Content-Disposition: attachment; filename="'.$filename.'"'
        );
        header('Cache-Control: max-age=0');
        $writer =
            new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );
        $writer->save('php://output');
        exit;
    }
}
