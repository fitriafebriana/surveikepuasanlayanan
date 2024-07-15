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
        // dd('a');
        $tambahlayanan = Kuisioner::latest()->paginate(5);
        return view('pages.dashboard.tambahlayanan.index', compact('tambahlayanan'));
    }

    public function create()
    {
        // $unsurs = Unsur::all();
        // dd('a');

        return view('pages.dashboard.tambahlayanan.create');
    }

    public function store(StoreKuesionerRequest $request)
    {
        // dd($request);
        try {
            Kuisioner::create($request->only('question'));
            return redirect()
                ->route('tambahlayanan.index')
                ->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat menyimpan data!', $th->getMessage()]]);
        }
    }

    public function edit(Kuisioner $tambahlayanan)
    {
        try {
            // $unsurs = Unsur::all();

            return view('pages.dashboard.tambahlayanan.edit', compact('tambahlayanan'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengambil data!', $th->getMessage()]]);
        }
    }

    public function update(UpdateKuesionerRequest $request, Kuisioner $tambahlayanan)
    {
        try {
            $tambahlayanan->question = $request->question;
            // $tambahlayanan->unsur_id = $request->unsur_id;
            $tambahlayanan->update();
            return redirect()->route('tambahlayanan.index', $tambahlayanan->id)->with('success', 'Data berhasil diedit!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['message' => ['Terjadi kesalahan saat mengedit data!', $th->getMessage()]]);
        }
    }

    public function destroy(Kuisioner $tambahlayanan)
    {
        // dd($tambahlayanan);
        try {
            Kuisioner::destroy($tambahlayanan->id);
            return redirect()->route('tambahlayanan.index')->with('success', 'Data berhasil dihapus!');
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
}