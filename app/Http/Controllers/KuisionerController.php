<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKuesionerRequest;
use App\Http\Requests\UpdateKuesionerRequest;
use App\Models\Kuisioner;
// use App\Models\Unsur;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    public function index()
    {
      
        $kuisioner = Kuisioner::latest()->paginate(5);
        return view('pages.dashboard.kuisioner.index', compact('kuisioner'));
    }

    public function create()
    {
        // $unsurs = Unsur::all();
        // dd('a');

        return view('pages.dashboard.kuisioner.create');
    }

    public function store(StoreKuesionerRequest $request)
    {
      
        // return $request;
        // dd($request);
        try {
            // Kuisioner::create($request->only('question'));
             Kuisioner::create([
                'question' => $request->question,
                'aktif' =>  1,
            ]);
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
       
        // return $request;
        if (isset($request->aktif)) {
            $aktif = 1;
        } else {
            $aktif = 0;
        }

        try {
            $kuisioner->question = $request->question;
            $kuisioner->aktif = $aktif;
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
        // try {
        //     Kuisioner::destroy($kuisioner->id);
        //     return redirect()->route('kuisioner.index')->with('success', 'Data berhasil dihapus!');
        // } catch (\Throwable $th) {
        //     return redirect()->back()
        //         ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data!', $th->getMessage()]]);
        // }
    }

    public function checks(Request $request)
    {
        // return $request;
       
        try {
            $action = $request->action;
            $checks = $request->checks;

            if ($action == 'delete') {
                Kuisioner::whereIn('id', $checks)->delete();
            } else if ($action == 'export'){
                return 'export';
                // Kuisioner::whereIn('id', $checks)->delete();
            }

            return redirect()
                ->back()
                ->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menghapus data', $th->getMessage()]]);
        }
    }

   
}