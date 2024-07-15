<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKuesionerRequest;
use App\Http\Requests\UpdateKuesionerRequest;
use App\Exports\LayananExport;
use App\Models\Kuisioner;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Excel;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        // dd('a');
        // return $request;
        // $layanan = Layanan::latest()->paginate(5);

      
        $query = Layanan::query()->join('hasils', 'layanans.id', '=', 'hasils.layanan_id');

        // $query = Layanan::join('hasils', 'layanans.id', '=', 'hasils.layanan_id')->query();

        if ($request->has('search') && $request->search != '') {
            $query->where('layanan', 'like', '%' . $request->search . '%')
                  ->orWhere('media', 'like', '%' . $request->search . '%')
                  ->orWhere('tglakses', 'like', '%' . $request->search . '%')
                  ->orWhere('layanans.created_at', 'like', '%' . $request->search . '%')
                  ->orWhere('feedback', 'like', '%' . $request->search . '%');
        }
    
        // $layanan = $query->latest()->paginate(5);
        $layanan = $query->latest('layanans.created_at')->paginate(5);
    
         return view('pages.dashboard.layanan.index', compact('layanan'));
    }

    public function create()
    {
        // $unsurs = Unsur::all();
        // dd('a');

        return view('pages.dashboard.kuisioner.create');
    }

    public function store(StoreKuesionerRequest $request)
    {
        // dd($request);
        try {
            Kuisioner::create($request->only('question'));
            return redirect()
                ->route('kuisioner.index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menyimpan data!', $th->getMessage()]]);
        }
    }

    public function edit(Kuisioner $kuisioner)
    {
        try {
            // $unsurs = Unsur::all();

            return view('pages.dashboard.kuisioner.edit', compact('kuisioner'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengambil data!', $th->getMessage()]]);
        }
    }

    public function update(UpdateKuesionerRequest $request, Kuisioner $kuisioner)
    {
        try {
            $kuisioner->question = $request->question;
            // $kuisioner->unsur_id = $request->unsur_id;
            $kuisioner->update();
            return redirect()->route('kuisioner.index', $kuisioner->id)->with('success', 'Data berhasil diedit!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengedit data!', $th->getMessage()]]);
        }
    }

    public function destroy(Kuisioner $kuisioner)
    {
        // dd($kuisioner);
        try {
            Kuisioner::destroy($kuisioner->id);
            return redirect()->route('kuisioner.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data!', $th->getMessage()]]);
        }
    }

    public function checks(Request $request)
    {
        // return $request;
        try {
            $action = $request->action;
            $checks = $request->checks;

            if ($action == 'delete') {
                Kuisioner::whereIn('id', $checks)->delete();
            }

            return redirect()
                ->back()
                ->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data', $th->getMessage()]]);
        }
    }

    public function export(Request $request)
    {
        // return $request;

         $tglawal = $request->tglawal;
          $tglakhir = $request->tglakhir;

         

        //  $data = Layanan::join('hasils', 'layanans.id', '=', 'hasils.layanan_id')
        // ->where('tglakses', '>=', $tglawal)
        // ->where('tglakses', '<=', $tglakhir)
        // ->select('layanans.id', 'layanans.tglakses', 'layanans.layanan', 'layanans.media', 'hasils.answer', 'layanans.feedback')
        // ->get();

        return Excel::download(new LayananExport($tglawal,$tglakhir), 'Export Hasil Survey' . '.xlsx');

        
    }
}