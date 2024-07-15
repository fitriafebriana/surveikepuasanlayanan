<?php

namespace App\Exports;

use App\Models\User;
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
use App\Models\ObjekPajak;
use App\Models\ObjekPajakJenis;

class ObjekPajakExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    protected $objek_pajak_jenis_id;
    protected $status;
    protected $orderby;
    protected $hotel_jenis_id;
    protected $restoran_jenis_id;
    protected $jenis_id;
    protected $objek_pajak_reklame_jenis_id;
    protected $objek_pajak_ppj_jenis_id;
    protected $objek_pajak_parkir_jenis_id;
    protected $objek_pajak_airtanah_jenis_id;
    protected $laporan_title;
    protected $data_jumlah;

    function __construct($objek_pajak_jenis_id,$status,$orderby,$hotel_jenis_id,$restoran_jenis_id,
    $jenis_id,$objek_pajak_reklame_jenis_id,$objek_pajak_ppj_jenis_id,
    $objek_pajak_parkir_jenis_id,$objek_pajak_airtanah_jenis_id) {
            $this->objek_pajak_jenis_id             = $objek_pajak_jenis_id;
            $this->status                           = $status;
            $this->orderby                          = $orderby;
            $this->hotel_jenis_id                   = $hotel_jenis_id;
            $this->restoran_jenis_id                = $restoran_jenis_id;
            $this->jenis_id                         = $jenis_id;
            $this->objek_pajak_reklame_jenis_id     = $objek_pajak_reklame_jenis_id;
            $this->objek_pajak_ppj_jenis_id         = $objek_pajak_ppj_jenis_id;
            $this->objek_pajak_parkir_jenis_id      = $objek_pajak_parkir_jenis_id;
            $this->objek_pajak_airtanah_jenis_id    = $objek_pajak_airtanah_jenis_id;

            $this->laporan_title = "LAPORAN OBJEK PAJAK";
    }

    public function collection()
    {
        // $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11);

        if ($this->objek_pajak_jenis_id) {
            $jenis = ObjekPajakJenis::find($this->objek_pajak_jenis_id);
            if ($this->status == 'all') {
                $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                ->where('objek_pajak_jenis_id', $this->objek_pajak_jenis_id)->get();
                if ($this->objek_pajak_jenis_id == 1) {
                    if ($this->hotel_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('ophotel.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $hotel_jenis_id = $this->hotel_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('ophotel.kelompok')
                            ->whereHas('ophotel.kelompok', function (
                                $where
                            ) use ($hotel_jenis_id) {
                                $where->where('id', $hotel_jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 2) {
                    if ($this->restoran_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('oprestoran.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $restoran_jenis_id = $this->restoran_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('oprestoran.kelompok')
                            ->whereHas('oprestoran.kelompok', function (
                                $where
                            ) use ($restoran_jenis_id) {
                                $where->where('id', $restoran_jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 3) {
                    if ($this->jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('ophiburan.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $jenis_id = $this->jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('ophiburan.kelompok')
                            ->whereHas('ophiburan.kelompok', function (
                                $where
                            ) use ($jenis_id) {
                                $where->where('id', $jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 4) {
                    if ($this->objek_pajak_reklame_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('opreklame.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $objek_pajak_reklame_jenis_id = $this->objek_pajak_reklame_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('opreklame.kelompok')
                            ->whereHas('opreklame.kelompok', function (
                                $where
                            ) use ($objek_pajak_reklame_jenis_id) {
                                $where->where(
                                    'id',
                                    $objek_pajak_reklame_jenis_id
                                );
                            })
                            ->orderBy($this->orderby)
                            ->get();
                        // return $data->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 7) {
                    if ($this->objek_pajak_airtanah_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('optanah.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $objek_pajak_airtanah_jenis_id =
                            $this->objek_pajak_airtanah_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->with('optanah.kelompok')
                            ->whereHas('optanah.kelompok', function (
                                $where
                            ) use ($objek_pajak_airtanah_jenis_id) {
                                $where->where(
                                    'id',
                                    $objek_pajak_airtanah_jenis_id
                                );
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif (
                    $this->objek_pajak_jenis_id == 5 ||
                    $this->objek_pajak_jenis_id == 6

                ) {
                    $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)->where('objek_pajak_jenis_id', $this->objek_pajak_jenis_id)
                    ->orderBy($this->orderby)
                    ->get();
                }

                $laporan_title = "$this->laporan_title " . $jenis->nickname();
            } elseif ($this->status == 't') {
                if ($this->objek_pajak_jenis_id == 1) {
                    if ($this->hotel_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('ophotel.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $hotel_jenis_id = $this->hotel_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('ophotel.kelompok')
                            ->whereHas('ophotel.kelompok', function (
                                $where
                            ) use ($hotel_jenis_id) {
                                $where->where('id', $hotel_jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 2) {
                    if ($this->restoran_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('oprestoran.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $restoran_jenis_id = $this->restoran_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('oprestoran.kelompok')
                            ->whereHas('oprestoran.kelompok', function (
                                $where
                            ) use ($restoran_jenis_id) {
                                $where->where('id', $restoran_jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 3) {
                    if ($this->jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('ophiburan.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $jenis_id = $this->jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('ophiburan.kelompok')
                            ->whereHas('ophiburan.kelompok', function (
                                $where
                            ) use ($jenis_id) {
                                $where->where('id', $jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 4) {
                    if ($this->objek_pajak_reklame_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('opreklame.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $objek_pajak_reklame_jenis_id =
                            $this->objek_pajak_reklame_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('opreklame.kelompok')
                            ->whereHas('opreklame.kelompok', function (
                                $where
                            ) use ($objek_pajak_reklame_jenis_id) {
                                $where->where(
                                    'id',
                                    $objek_pajak_reklame_jenis_id
                                );
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 7) {
                    if ($this->objek_pajak_airtanah_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('optanah.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $objek_pajak_airtanah_jenis_id =
                            $this->objek_pajak_airtanah_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 't')
                            ->with('optanah.kelompok')
                            ->whereHas('optanah.kelompok', function (
                                $where
                            ) use ($objek_pajak_airtanah_jenis_id) {
                                $where->where(
                                    'id',
                                    $objek_pajak_airtanah_jenis_id
                                );
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif (
                    $this->objek_pajak_jenis_id == 5 ||
                    $this->objek_pajak_jenis_id == 6
                ) {
                    $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                        ->where('objek_pajak_jenis_id', $this->objek_pajak_jenis_id)
                        ->where('aktif', 't')
                        ->orderBy($this->orderby)
                        ->get();
                }
            } elseif ($this->status == 'f') {
                if ($this->objek_pajak_jenis_id == 1) {
                    if ($this->hotel_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('ophotel.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $hotel_jenis_id = $this->hotel_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('ophotel.kelompok')
                            ->whereHas('ophotel.kelompok', function (
                                $where
                            ) use ($hotel_jenis_id) {
                                $where->where('id', $hotel_jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 2) {
                    if ($this->restoran_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('oprestoran.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $restoran_jenis_id = $this->restoran_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('oprestoran.kelompok')
                            ->whereHas('oprestoran.kelompok', function (
                                $where
                            ) use ($restoran_jenis_id) {
                                $where->where('id', $restoran_jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 3) {
                    if ($this->jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('ophiburan.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $jenis_id = $this->jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('ophiburan.kelompok')
                            ->whereHas('ophiburan.kelompok', function (
                                $where
                            ) use ($jenis_id) {
                                $where->where('id', $jenis_id);
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 4) {
                    if ($this->objek_pajak_reklame_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('opreklame.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $objek_pajak_reklame_jenis_id =
                            $this->objek_pajak_reklame_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('opreklame.kelompok')
                            ->whereHas('opreklame.kelompok', function (
                                $where
                            ) use ($objek_pajak_reklame_jenis_id) {
                                $where->where(
                                    'id',
                                    $this->objek_pajak_reklame_jenis_id
                                );
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif ($this->objek_pajak_jenis_id == 7) {
                    if ($this->objek_pajak_airtanah_jenis_id == null) {
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('optanah.kelompok')
                            ->orderBy($this->orderby)
                            ->get();
                    } else {
                        $objek_pajak_airtanah_jenis_id =
                            $this->objek_pajak_airtanah_jenis_id;
                        $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                            ->where(
                                'objek_pajak_jenis_id',
                                $this->objek_pajak_jenis_id
                            )
                            ->where('aktif', 'f')
                            ->with('optanah.kelompok')
                            ->whereHas('optanah.kelompok', function (
                                $where
                            ) use ($objek_pajak_airtanah_jenis_id) {
                                $where->where(
                                    'id',
                                    $objek_pajak_airtanah_jenis_id
                                );
                            })
                            ->orderBy($this->orderby)
                            ->get();
                    }
                } elseif (
                    $this->objek_pajak_jenis_id == 5 ||
                    $this->objek_pajak_jenis_id == 6
                ) {
                    $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)
                        ->where('objek_pajak_jenis_id', $this->objek_pajak_jenis_id)
                        ->where('aktif', 'f')
                        ->orderBy($this->orderby)
                        ->get();
                }
                $laporan_title = $this->laporan_title . ' ' . $jenis->nickname();
            } else {
                if ($this->status == 'all') {
                    $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)->orderBy($this->orderby)->get();
                } elseif ($this->status == 't') {
                    $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)->orderBy($this->orderby)->get();
                } elseif ($this->status == 'f') {
                    $data = ObjekPajak::where('objek_pajak_jenis_id', '!=', 11)->orderBy($this->orderby)->get();
                }
            }

       
        // $data->orderBy($this->orderby);
        $data_arr = new Collection([]);
        $index = 0;
      
        foreach($data as $item){
            $index++;
         
                if($item->aktif == true){
                    $aktif = 'Aktif';
                }else{
                    $aktif = 'Tidak Aktif';
                }

                if($jenis->id == 1){
                    $kelompok = $item->ophotel->kelompok->nama ?? '-';
                }elseif($jenis->id == 2){
                    $kelompok = $item->oprestoran->kelompok->nama ?? '-';
                }elseif($jenis->id == 3){
                    $kelompok = $item->ophiburan->kelompok->nama ?? '-';
                }elseif($jenis->id == 4){
                    $kelompok = $item->opreklame->kelompok->nama ?? '-';
                }elseif($jenis->id == 5){
                    $kelompok = 'Pajak Penerangan Jalan';
                }elseif($jenis->id == 6){
                    $kelompok = 'Parkir';
                }elseif($jenis->id == 7){
                    $kelompok = $item->optanah->kelompok->nama ?? '-';
                }else{
                    $kelompok = '';
                }

            if(!$jenis){
                $data_arr->push([
                    'no'         => $index,
                    'nop'        => $item->nop,
                    // 'jenis'      => $item->jenis->nickname(),
                    'kelompok'   => $kelompok,
                    'nama'       => $item->nama,
                    'jalan'      => $item->jalan->nama,
                    'pemilik'    => $item->pemilik->nama,
                    'aktif'      => $aktif,
                ]);

           }else{
                $data_arr->push([
                    'no'         => $index,
                    'nop'        => $item->nop,
                    // 'jenis'      => '',
                    'kelompok'   => $kelompok,
                    'nama'       => $item->nama,
                    'jalan'      => $item->jalan->nama,
                    'pemilik'    => $item->pemilik->nama,
                    'aktif'      => $aktif,
            ]);

           }
        }
           
        }

        

        return $data_arr;
        }

    public function headings() :array
    {
        return [
            [$this->laporan_title],
            [
                'NO',
                'NOP',
                // 'JENIS OBJEK PAJAK',
                'KELOMPOK',
                'NAMA OP',
                'ALAMAT OP',
                'NAMA WP',
                'STATUS',
            ]
        ];
        return [
                'NO',
                'NOP',
                // 'JENIS OBJEK PAJAK',
                'KELOMPOK',
                'NAMA OP',
                'ALAMAT OP',
                'NAMA WP',
                'STATUS',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            // 'H' => NumberFormat::FORMAT_TEXT,

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->mergeCells('A1:G1');

                $event->sheet->getDelegate()->getStyle('A1:G2')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(8);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                // $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(30);
                $event->sheet->styleCells(
                    'A2:G2',
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['argb' => 'c7c7c7']
                        ]
                    ]
                );

                $event->sheet->styleCells(
                    'A3:G'.($this->data_jumlah+2),
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ]
                    ]
                );
            },
        ];
    }
}
