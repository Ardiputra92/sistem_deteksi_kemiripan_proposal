<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Similarity; // Sesuaikan dengan model yang kamu pakai
use App\Models\Threshold;


class ResultController extends Controller
{

    // public function index()
    // {
    //     $similarities = Similarity::with('user')->get();
    //     $threshold = 25; // Default atau ambil dari database jika kamu sudah menyimpannya
    //     return view('result.index', compact('similarities', 'threshold'));
    // }

    public function index()
    {
        $threshold = Threshold::first()->value ?? 25;
        $similarities = Similarity::with('user')->get();

        return view('result.index', compact('similarities', 'threshold'));
    }


    public function updateThreshold(Request $request)
    {
        $request->validate([
            'threshold' => 'required|integer|min:0|max:100',
        ]);

        // Selalu update baris pertama
        $threshold = Threshold::first();

        if ($threshold) {
            $threshold->update(['value' => $request->threshold]);
        } else {
            Threshold::create(['value' => $request->threshold]);
        }

        return redirect()->route('result.index')->with('success', 'Threshold berhasil diperbarui!');
    }

    public function create()
    {
        return view('result.create');
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data
    }

    public function edit($id)
    {
        $similarity = Similarity::findOrFail($id);
        return view('result.edit', compact('similarity'));
    }

    public function update(Request $request, $id)
    {
        // Update data
    }

    public function destroy($id)
    {
        $similarity = Similarity::findOrFail($id);
        $similarity->delete();

        return redirect()->route('result.index')->with('success', 'Data berhasil dihapus.');
    }
}
