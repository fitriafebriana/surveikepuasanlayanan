<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Feedback;
use App\Models\Kuisioner;
use App\Models\Layanan;
use App\Models\Responden;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

function getIkmData($respondens, $kuesioners)
{
    $data = [];

    $bobotNilaiTertimbang = 1;
    if (count($kuesioners) > 0) {
        $bobotNilaiTertimbang = 1 / count($kuesioners);
    }

    $nilaiPersepsiPerUnit = [];
    foreach ($respondens as $keyResponden => $responden) {
        foreach ($responden->answers as $keyAnswer => $answer) {
            $nilaiPersepsiPerUnit[$keyResponden][$keyAnswer] = (object) [
                'question' => $answer->kuisioner->question,
                'answer' => $answer->answer
            ];
        }
    }

    $totalAnswer = [];
    foreach ($nilaiPersepsiPerUnit as $key => $array) {
        for ($i = 0; $i < count($array); $i++) {
            if (!isset($totalAnswer[$i])) {
                $totalAnswer[$i] = 0;
            }
            $totalAnswer[$i] += $array[$i]->answer;
        }
    }

    foreach ($totalAnswer as $key => $value) {
        $data[$key] = (object) [
            'question' => $kuesioners[$key]->question,
            'totalNilaiPersepsiPerUnit' => $value
        ];
    }

    foreach ($data as $key => $value) {
        $data[$key] = (object) [
            'question' => $value->question,
            'totalNilaiPersepsiPerUnit' => $value->totalNilaiPersepsiPerUnit,
            'NRRPerUnsur' => $value->totalNilaiPersepsiPerUnit / count($respondens)
        ];
    }

    foreach ($data as $key => $value) {
        $data[$key] = (object) [
            'question' => $value->question,
            'totalNilaiPersepsiPerUnit' => $value->totalNilaiPersepsiPerUnit,
            'NRRPerUnsur' => $value->NRRPerUnsur,
            'NRRTertimbangUnsur' => $value->NRRPerUnsur * $bobotNilaiTertimbang
        ];
    }

    $IKM = 0;
    foreach ($data as $value) {
        $IKM += $value->NRRTertimbangUnsur;
    }

    $konversiIKM = $IKM * 25;

    $ikm = [
        'nilaiIkmTertimbang' => number_format($IKM, 2),
        'ikmUnit' => number_format($konversiIKM, 2),
        'mutu' => nilaPersepsi($konversiIKM)->mutu,
        'kinerja' => nilaPersepsi($konversiIKM)->kinerja,
        'responden' => (object) [
            'jumlah' => $respondens->count(),
            'laki' => $respondens->where('gender', 'Laki-laki')->count(),
            'perempuan' => $respondens->where('gender', 'Perempuan')->count(),
            'sd' => $respondens->where('education', 'SD')->count(),
            'smp' => $respondens->where('education', 'SMP')->count(),
            'sma' => $respondens->where('education', 'SMA')->count(),
            'smk' => $respondens->where('education', 'SMK')->count(),
            's1' => $respondens->where('education', 'S1')->count(),
            's2' => $respondens->where('education', 'S2')->count(),
            's3' => $respondens->where('education', 'S3')->count(),
        ]
    ];

    return $ikm;
}

class DasborController extends Controller
{
    public function index()
    {
        // return 'a';
        $datakuesioners = Kuisioner::all();
        $dataAnswers = Hasil::all();
        $dataRespondens = Layanan::all();
        $dataFeedbacks = Layanan::all();

        $total = (object) [
            'kuisioner' => $datakuesioners->count(),
            'answer' => $dataAnswers->count(),
            'responden' => $dataRespondens->count(),
            'feedback' => $dataFeedbacks->count()
        ];

        $today = Carbon::now();
        $dateArray = [];

        for ($i = 0; $i < 7; $i++) {
            $dateArray[] = $today->copy()->subDays($i)->format('Y-m-d');
        }
        $dateArray = array_reverse($dateArray);

        $dailyAnswers = [];
        foreach ($dateArray as $key => $date) {
            $dailyAnswers[$date] = [
                (object) [
                    'label' => 0,
                    'total' => Hasil::where('answer', 1)
                        ->whereHas('layanan', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                        })
                        ->count()
                ],
                (object) [
                    'label' => 1,
                    'total' => Hasil::where('answer', 2)
                        ->whereHas('layanan', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                        })
                        ->count()
                ],
                (object) [
                    'label' => 2,
                    'total' => Hasil::where('answer', 3)
                        ->whereHas('layanan', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                        })
                        ->count()
                ],
                (object) [
                    'label' => 3,
                    'total' => Hasil::where('answer', 4)
                        ->whereHas('layanan', function ($query) use ($date) {
                            $query->whereDate('created_at', $date);
                        })
                        ->count()
                ],
            ];
        }

        $answers = (object) [
            'total' => $total->answer,
            'details' => [
                [
                    'label' => rateLabel(1),
                    'total' => $dataAnswers->where('answer', 1)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 1)->count(), $total->answer)
                ],
                [
                    'label' => rateLabel(2),
                    'total' => $dataAnswers->where('answer', 2)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 2)->count(), $total->answer)
                ],
                [
                    'label' => rateLabel(3),
                    'total' => $dataAnswers->where('answer', 3)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 3)->count(), $total->answer)
                ],
                [
                    'label' => rateLabel(4),
                    'total' => $dataAnswers->where('answer', 4)->count(),
                    'percentage' => getPercentage($dataAnswers->where('answer', 4)->count(), $total->answer)
                ],
            ],
            'daily' => $dailyAnswers
        ];

        $colors = (object) [
            'red' => '#F63326',
            'orange' => '#F07F00',
            'yellow' => '#ECBD00',
            'green' => '#4CD440',
            'blue' => '#5540F5',
            'purple' => '#A736FF',
            'pink' => '#FF6EC7',
            'brown' => '#8B572A',
            'gray' => '#757575',
            'teal' => '#00BFA5',
            'cyan' => '#00BCD4',
            'indigo' => '#3F51B5',
            'lime' => '#CDDC39',
            'amber' => '#FFC107'
        ];

       

        // Menghitung persentase responden berdasarkan layanan
        $dataGrafiklayanan = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->where('layanan', 'perpustakaan')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('layanan', 'konstat')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('layanan', 'prodstat')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('layanan', 'rekomstat')->count(), $dataRespondens->count()), 2),
                
            ],
            'labels' => ['Perpustakaan', 'Konsultsi Statistik', 'Penjualan Produk Statistik', 'Rekomendasi Kegiatan Statistik'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->orange, $colors->yellow, $colors->green]
        ];

           // Menghitung persentase responden berdasarkan media
           $dataGrafikmedia = (object) [
            'series' => [
                (int) number_format(getPercentage($dataRespondens->where('media', 'PST')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Website')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Email')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Whatsapp')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Zoom')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Silastik')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Romantik')->count(), $dataRespondens->count()), 2),
                (int) number_format(getPercentage($dataRespondens->where('media', 'Medsos')->count(), $dataRespondens->count()), 2),

                
            ],
            'labels' => ['PST', 'Website', 'Email', 'Whatsapp','Zoom','Silastik','Romantik','Medsos'],
            'total' => $dataRespondens->count(),
            'colors' => [$colors->red, $colors->orange, $colors->yellow, $colors->green, $colors->blue, $colors->purple, $colors->pink, $colors->brown]
        ];

        return view('pages.dashboard.index', compact('total', 'answers','dataGrafiklayanan', 'dataGrafikmedia' ));
    }

    public function ikm(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuisioner::all();
       

        extract(getIKM($respondens, $kuesioners));

        return view('pages.dashboard.ikm.index', compact('data', 'IKM', 'konversiIKM', 'bobotNilaiTertimbang'));
    }

    public function ikm_export(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuisioner::all();

        $ikm = getIkmData($respondens, $kuesioners);
        $data = getIkm($respondens, $kuesioners);

        if (count($data['data']) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $unsurSurvey = [];
        foreach ($data['data'] as $dataItem) {
            $filteredKuesioner = $kuesioners->where('question', $dataItem->question)->first();
            $unsur = $filteredKuesioner->unsur->unsur;

            if (!isset($unsurSurvey[$unsur])) {
                $unsurSurvey[$unsur] = [
                    'total' => 0,
                    'count' => 0,
                    'average' => 0,
                ];
            }

            $unsurSurvey[$unsur]['total'] += $dataItem->NRRPerUnsur;
            $unsurSurvey[$unsur]['count']++;
            $unsurSurvey[$unsur]['average'] = $unsurSurvey[$unsur]['total'] / $unsurSurvey[$unsur]['count'];
        }

        $pdf = Pdf::loadView('export.ikm', compact('ikm', 'data', 'unsurSurvey'));

        return $pdf->download('Laporan IKM.pdf');
    }

    public function ikm_preview(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuisioner::all();

        $ikm = getIkmData($respondens, $kuesioners);
        $data = getIkm($respondens, $kuesioners);

        if (count($data['data']) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $unsurSurvey = [];
        foreach ($data['data'] as $dataItem) {
            $filteredKuesioner = $kuesioners->where('question', $dataItem->question)->first();
            $unsur = $filteredKuesioner->unsur->unsur;

            if (!isset($unsurSurvey[$unsur])) {
                $unsurSurvey[$unsur] = [
                    'total' => 0,
                    'count' => 0,
                    'average' => 0,
                ];
            }

            $unsurSurvey[$unsur]['total'] += $dataItem->NRRPerUnsur;
            $unsurSurvey[$unsur]['count']++;
            $unsurSurvey[$unsur]['average'] = $unsurSurvey[$unsur]['total'] / $unsurSurvey[$unsur]['count'];
        }

        $pdf = Pdf::loadView('export.ikm', compact('ikm', 'data', 'unsurSurvey'));

        return $pdf->stream();
    }

    public function ikm_export_table(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuisioner::all();
      

        extract(getIKM($respondens, $kuesioners));

        if (count($data) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $pdf = Pdf::loadView('export.ikm-table', compact('data', 'IKM', 'konversiIKM', 'bobotNilaiTertimbang'));

        return $pdf->download('Laporan IKM.pdf');
    }

    public function ikm_preview_table(Request $request)
    {
        $query = Responden::query();

        if (!$request->has('start_date') || !$request->has('end_date')) {
            $oldestResponden = Responden::oldest('created_at')->first();
            $newestResponden = Responden::latest('created_at')->first();

            $dates = [
                'start_date' => $oldestResponden ? $oldestResponden->created_at->format('Y-m-d') : Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => $newestResponden ? $newestResponden->created_at->format('Y-m-d') : Carbon::now()->format('Y-m-d')
            ];

            return redirect()->route('ikm.index', array_merge($request->all(), $dates));
        }

        if ($request->has('filter') && $request->has('filter_by') && $request->filter != 'Semua') {
            $query->where($request->filter_by, $request->filter);
        }

        $startDate = Carbon::parse($request->start_date)->subDay()->startOfDay()->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->addDay()->endOfDay()->toDateTimeString();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $respondens = $query->get();
        $kuesioners = Kuisioner::all();

        extract(getIKM($respondens, $kuesioners));

        if (count($data) == 0) {
            return redirect()->back()
                ->withErrors(['message' => ['Data Kosong']]);
        }

        $pdf = Pdf::loadView('export.ikm-table', compact('data', 'IKM', 'konversiIKM', 'bobotNilaiTertimbang'));

        return $pdf->stream();
    }

    

  

   
}
