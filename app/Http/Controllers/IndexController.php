<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Feedback;
use App\Models\Kuisioner;
use App\Models\Responden;
use App\Models\Layanan;
use App\Models\Hasil;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index()
    {
        $datakuesioners = Kuisioner::where('aktif', '!=', '0')->get();
        $dataAnswers = Hasil::all();
        $dataRespondens = Responden::all();
        $dataFeedbacks = Feedback::all();

        $total = (object) [
            'kuisioner' => $datakuesioners->count(),
            'answer' => $dataAnswers->count(),
            'responden' => $dataRespondens->count(),
            'feedback' => $dataFeedbacks->count()
        ];

        $today = Carbon::now();
        $dateArray = [];
        for ($i = 0; $i <= 7; $i++) {
            $dateArray[] = $today->subDays($i)->toDateString();
        }
        $dateArray = array_reverse($dateArray);

        $dailyAnswers = [];
        foreach ($dateArray as $key => $date) {
            $dailyAnswers[$date] = [
                (object) [
                    'label' => 0,
                    'total' => Answer::where('answer', 1)->whereDate('created_at', $date)->count()
                ],
                (object) [
                    'label' => 1,
                    'total' => Answer::where('answer', 2)->whereDate('created_at', $date)->count()
                ],
                (object) [
                    'label' => 2,
                    'total' => Answer::where('answer', 3)->whereDate('created_at', $date)->count()
                ],
                (object) [
                    'label' => 3,
                    'total' => Answer::where('answer', 4)->whereDate('created_at', $date)->count()
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

        return view('pages.public.index', compact('total', 'answers'));
    }

    public function kuisioner(Request $request)
    {
        try {
            $step = $request->get('step');
            $question = $request->get('question');

            if (!$step) {
                return redirect()
                    ->route('kuisioner', ['step' => 1]);
            }

            $kuisioner = Kuisioner::where('aktif', '!=', '0')->get();
            $totalKuesioner = count($kuisioner);

            if(count($kuisioner) === 0) {
                throw new \Error('Maaf, kuisioner belum tersedia');
            }

            if ($step == 1) {
              
                return view('pages.public.kuisioner', compact('step', 'totalKuesioner'));
            }

            if ($step == 2) {
                $data = $request->all();

                $validator = Validator::make($data, [
                    'layanan' => 'required',
                    'media' => 'required',
                    'tgl' => 'required|date',
                   
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator);
                }

                $semuaPertanyaanTerisi = true;
                for ($i = 1; $i <= $totalKuesioner; $i++) {
                    $questionKey = "question" . $i;
                    if (!isset($data[$questionKey]) || empty($data[$questionKey])) {
                        if($question == count($kuisioner)+1) {
                            throw new \Error('Isi semua kuisioner!');
                        };
                        $semuaPertanyaanTerisi = false;
                        break;
                    }
                }

                if ($semuaPertanyaanTerisi) {
                    $data['step'] = 3;
                    $step = $data['step'];
                    return redirect()
                        ->route('kuisioner', compact('kuisioner', 'data', 'step'));
                }

                $kuisioner = $kuisioner[$question - 1];

                $data['question'] = $question - 1;
                $previous = $question == 1 ? '#' : route('kuisioner', $data);
                $data['question'] = $question + 1;
                $next = $question == $totalKuesioner ? '#' : route('kuisioner', $data);

                return view('pages.public.kuisioner', compact('kuisioner', 'totalKuesioner', 'step', 'next', 'previous', 'question', 'data'));
            }

            if ($step == 3) {
                $data = $request->data;
                $step = $request->step;

                return view('pages.public.kuisioner', compact('kuisioner', 'data', 'step'));
            }

            return redirect()
                ->route('kuisioner', ['step' => 1]);
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => ['Terjadi kesalahan!', $th->getMessage()]]);
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        // return $request;
        try {
            
            $responden = Layanan::create([
                'layanan' => $request->layanan,
                'media' => $request->media,
                'tglakses' => $request->tgl,
                'feedback' => $request->feedback
            ]);
            foreach ($request->answers as $answer) {
                $answerData = json_decode($answer, true);

               
                Hasil::create([
                    'kuesioner_id' => (int) $answerData['idKuesioner'],
                    'layanan_id' => (int) $responden->id,
                    'answer' => (int) $answerData['kuesionerAnswer']
                ]);
            }
            return redirect()
                ->route('index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => ['Terjadi kesalahan!', $th->getMessage()]]);
        }
    }
}
