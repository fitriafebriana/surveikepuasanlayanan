<?php

namespace App\Exports;

use App\Models\Layanan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;
use App\Models\Hasil;


class LayananExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    protected $tglawal;
    protected $tglakhir;
    protected $laporan_title;


    function __construct($tglawal, $tglakhir)
    {
        $this->tglawal = $tglawal;
        $this->tglakhir = $tglakhir;
        $this->laporan_title = "SURVEY KEPUASAN";

    }

    public function collection()
    {
        $data = Layanan::join('hasils', 'layanans.id', '=', 'hasils.layanan_id')
        ->where('layanans.tglakses', '>=', $this->tglawal)
        ->where('layanans.tglakses', '<=', $this->tglakhir)
        ->get();

        // $data = Layanan::get();
       
        $data_arr = new Collection([]);

        $index = 0;
      
        foreach($data as $item){
            $index++;
          
            $jawaban = '';
            switch ($item->answer) {
                case 1:
                    $jawaban = 'KURANG PUAS';
                    break;
                case 2:
                    $jawaban = 'TIDAK PUAS';
                    break;
                case 3:
                    $jawaban = 'PUAS';
                    break;
                case 4:
                    $jawaban = 'SANGAT PUAS';
                    break;
                default:
                    $jawaban = 'Tidak Diketahui';
                    break;
            }
          
            $data_arr->push([
                'no'        => $index,
                'tglakses'  => $item->tglakses,
                'layanan'   => $item->layanan,
                'media'     => $item->media,
                'jawaban'   => $jawaban,
                'feedback'  => $item->feedback,   
            ]); 
        }
        return $data_arr;
    }

    public function headings() :array
    {
        return [
            [$this->laporan_title],
            [
                'No',
                'Tanggal Akses',
                'Layanan',
                'Media Yang Diakses',
                'Jawaban',
                'Saran dan Kritik',
            ]
        ];
        return [
            'No',
            'Tanggal Akses',
            'Layanan',
            'Media Yang Diakses',
            'Jawaban',
            'Saran dan Kritik',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:F1');
                $event->sheet->getDelegate()->getStyle('A1:F2')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(50);

                
                // $event->sheet->styleCells(
                //     'A2:E2',
                //     [
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                //                 'color' => ['argb' => '000000'],
                //             ],
                //         ],
                //         'fill' => [
                //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //             'color' => ['argb' => 'c7c7c7']
                //         ]
                //     ]
                // );

                // $event->sheet->styleCells(
                //     // 'A3:H'.($this->data_jumlah+2),
                //     [
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //                 'color' => ['argb' => '000000'],
                //             ],
                //         ]
                //     ]
                // );
            },
        ];
    }
    
}
