<?php

namespace App\Exports;

use App\Models\Layanan;
use App\Models\Hasil;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class LayananExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tglawal;
    protected $tglakhir;

    public function __construct($tglawal, $tglakhir)
    {
        $this->tglawal = $tglawal;
        $this->tglakhir = $tglakhir;
    }

    public function collection()
    {
        $data = Layanan::join('hasils', 'layanans.id', '=', 'hasils.layanan_id')
            ->where('layanans.tglakses', '>=', $this->tglawal)
            ->where('layanans.tglakses', '<=', $this->tglakhir)
            ->select('layanans.id', 'layanans.tglakses', 'layanans.layanan', 'layanans.media', 'hasils.answer', 'layanans.feedback')
            ->get();

        $index = 0;
        $data_arr = [];

        foreach ($data as $item) {
            $index++;
            $data_arr[] = [
                'no'        => $index,
                'tglakses'  => $item->tglakses,
                'layanan'   => $item->layanan,
                'media'     => $item->media,
                'jawaban'   => $this->getAnswerLabel($item->answer),
                'feedback'  => $item->feedback,
            ];
        }

        return new Collection($data_arr);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Akses',
            'Layanan',
            'Media Yang Diakses',
            'Jawaban',
            'Saran dan Kritik',
        ];
    }

    public function map($model): array
    {
        return [
            $model['no'],
            $model['tglakses'],
            $model['layanan'],
            $model['media'],
            $model['jawaban'],
            $model['feedback'],
        ];
    }

    private function getAnswerLabel($answer)
    {
        switch ($answer) {
            case 1:
                return 'Tidak Puas';
            case 2:
                return 'Kurang Puas';
            case 3:
                return 'Puas';
            case 4:
                return 'Sangat Puas';
            default:
                return 'Tidak Diketahui';
        }
    }
}
