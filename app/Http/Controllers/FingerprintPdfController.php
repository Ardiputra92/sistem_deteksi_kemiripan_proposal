<?php

namespace App\Http\Controllers;

use App\Models\FingerprintPdf;
use App\Models\Fingerprint;
use Illuminate\Http\Request;

class FingerprintPdfController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel fingerprints
        $fingerprints = Fingerprint::all();

        // Mengirim data ke view pdf.index
        return view('pdf.index', ['fingerprints' => $fingerprints]);
    }

    public function create()
    {
        $data = 10;
        return view('pdf.create', ['data' => $data]);
    }

    public function store(Request $request)
    {
        // Simpan file ke dalam direktori penyimpanan
        $file = $request->file('pdf');
        $originalFileName = $file->getClientOriginalName();
        $file->move(public_path('uploads'), $originalFileName);

        // Ambil teks dari file PDF
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(public_path('uploads/' . $originalFileName));
        $text = $pdf->getText();

        // Proses teks PDF menggunakan FingerprintPdf
        $n = $request->input('n');
        $window = $request->input('window');
        $prima = $request->input('prima');

        $w = new FingerprintPdf($text);
        $w->SetPrimeNumber($prima);
        $w->SetNGramValue($n);
        $w->SetNWindowValue($window);
        $w->process();

        // Persiapkan data untuk disimpan di database
        $s1 = '';
        foreach ($w->GetNGramFirst() as $ng) {
            $s1 .= $ng . ' ';
        }

        $s3 = '';
        foreach ($w->GetRollingHashFirst() as $rl) {
            $s3 .= $rl . ' ';
        }

        $wdf = $w->GetWindowFirst();
        $sWf = '';
        for ($i = 0; $i < count($wdf); $i++) {
            $s = '';
            for ($j = 0; $j < $window; $j++) {
                $s .= $wdf[$i][$j] . ' ';
            }
            $sWf .= "W-" . ($i + 1) . " : {" . rtrim($s, ' ') . "}\n";
        }

        $s7 = '';
        foreach ($w->GetFingerprintsFirst() as $fp) {
            $s7 .= $fp . ' ';
        }

        $count_fingers1 = count($w->GetFingerprintsFirst());
        $count_ngram1 = count($w->GetNGramFirst());
        $count_hash1 = count($w->GetRollingHashFirst());
        $count_window1 = count($w->GetWindowFirst());

        // Simpan data ke dalam tabel fingerprints_table
        $fingerprint = new Fingerprint();
        $fingerprint->judul = $request->input('judul');
        $fingerprint->file_dokumen = $originalFileName; // Hanya menyimpan nama file
        $fingerprint->n_gram = rtrim($s1, ' ');
        $fingerprint->hashing = rtrim($s3, ' ');
        $fingerprint->winnowing = $sWf;
        $fingerprint->fingerprint = rtrim($s7, ' ');

        // Tambahkan nilai-nilai total ke kolom-kolom yang sesuai
        $fingerprint->total_fingerprint = $count_fingers1;
        $fingerprint->total_ngram = $count_ngram1;
        $fingerprint->total_hash = $count_hash1;
        $fingerprint->total_window = $count_window1;

        $fingerprint->save();

        $result = [
            'nGramFirst' => rtrim($s1, ' '),
            'rollingHashFirst' => rtrim($s3, ' '),
            'windowFirst' => $sWf,
            'FingerprintsFirst' => rtrim($s7, ' '),
            'countFinger1' => $count_fingers1,
            'countNgram1' => $count_ngram1,
            'countHash1' => $count_hash1,
            'countWindow1' => $count_window1,
        ];

        return redirect()->route('pdf.index')->with('success', 'Document has been added successfully');
    }

    public function destroy($id)
    {
        // Temukan dokumen berdasarkan ID
        $fingerprint = Fingerprint::findOrFail($id);

        // Hapus file fisik dari sistem file
        $filePath = public_path('uploads/' . $fingerprint->file_dokumen);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dari database
        $fingerprint->delete();

        return redirect()->route('pdf.index')->with('success', 'Document has been deleted successfully');
    }

    public function show($id)
    {
        // Temukan dokumen berdasarkan ID
        $fingerprint = Fingerprint::findOrFail($id);

        // Tampilkan halaman detail dengan data dokumen
        return view('pdf.show', compact('fingerprint'));
    }

    public function edit($id)
    {
        // Temukan dokumen berdasarkan ID
        $fingerprint = Fingerprint::findOrFail($id);

        // Tampilkan halaman edit dengan data dokumen
        return view('pdf.edit', compact('fingerprint'));
    }

    public function update(Request $request, $id)
    {
        // Temukan dokumen berdasarkan ID
        $fingerprint = Fingerprint::findOrFail($id);

        // Simpan file ke dalam direktori penyimpanan jika ada perubahan file
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $originalFileName = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $originalFileName);

            // Hapus file lama jika ada
            $oldFilePath = public_path('uploads/' . $fingerprint->file_dokumen);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            // Update nama file baru
            $fingerprint->file_dokumen = $originalFileName;

            // Ambil teks dari file PDF baru
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile(public_path('uploads/' . $originalFileName));
            $text = $pdf->getText();

            // Proses teks PDF menggunakan FingerprintPdf
            $n = $request->input('n');
            $window = $request->input('window');
            $prima = $request->input('prima');

            $w = new FingerprintPdf($text);
            $w->SetPrimeNumber($prima);
            $w->SetNGramValue($n);
            $w->SetNWindowValue($window);
            $w->process();

            // Persiapkan data untuk disimpan di database
            $s1 = '';
            foreach ($w->GetNGramFirst() as $ng) {
                $s1 .= $ng . ' ';
            }

            $s3 = '';
            foreach ($w->GetRollingHashFirst() as $rl) {
                $s3 .= $rl . ' ';
            }

            $wdf = $w->GetWindowFirst();
            $sWf = '';
            for ($i = 0; $i < count($wdf); $i++) {
                $s = '';
                for ($j = 0; $j < $window; $j++) {
                    $s .= $wdf[$i][$j] . ' ';
                }
                $sWf .= "W-" . ($i + 1) . " : {" . rtrim($s, ' ') . "}\n";
            }

            $s7 = '';
            foreach ($w->GetFingerprintsFirst() as $fp) {
                $s7 .= $fp . ' ';
            }

            $count_fingers1 = count($w->GetFingerprintsFirst());
            $count_ngram1 = count($w->GetNGramFirst());
            $count_hash1 = count($w->GetRollingHashFirst());
            $count_window1 = count($w->GetWindowFirst());

            // Update data dokumen
            $fingerprint->judul = $request->input('judul');
            $fingerprint->n_gram = rtrim($s1, ' ');
            $fingerprint->hashing = rtrim($s3, ' ');
            $fingerprint->winnowing = $sWf;
            $fingerprint->fingerprint = rtrim($s7, ' ');

            // Tambahkan nilai-nilai total ke kolom-kolom yang sesuai
            $fingerprint->total_fingerprint = $count_fingers1;
            $fingerprint->total_ngram = $count_ngram1;
            $fingerprint->total_hash = $count_hash1;
            $fingerprint->total_window = $count_window1;

            $fingerprint->save();
        } else {
            // Jika tidak ada perubahan file, hanya update data lainnya
            $fingerprint->update($request->except('pdf'));
        }

        return redirect()->route('pdf.index')->with('success', 'Document has been updated successfully');
    }
}
