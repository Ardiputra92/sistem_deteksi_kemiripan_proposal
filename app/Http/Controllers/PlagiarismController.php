<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fingerprint;
use App\Models\FingerprintPdf;
use App\Models\Similarity;
use Illuminate\Support\Facades\Session;
use App\Models\Threshold;

class PlagiarismController extends Controller
{

    public function index()
    {
        // Ambil threshold dari database, fallback ke 25 kalau belum ada
        $threshold = Threshold::first()->value ?? 25;

        if (auth()->user()->level === 'admin') {
            $similarities = Similarity::all();
        } else {
            $similarities = Similarity::where('user_id', auth()->id())->get();
        }

        return view('plagiarism_pdf.index', compact('similarities', 'threshold'));
    }


    public function create()
    {
        return view('plagiarism_pdf.compare');
    }

    public function compare(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048',
            'n' => 'required|integer',
            'window' => 'required|integer',
            'prima' => 'required|integer',
            'title' => 'required|string',
        ]);

        $uploadedFile = $request->file('pdf');
        $uploadedFile->move(public_path('uploads'), $uploadedFile->getClientOriginalName());

        $parser = new \Smalot\PdfParser\Parser();
        $uploadedPdf = $parser->parseFile(public_path('uploads/' . $uploadedFile->getClientOriginalName()));
        $uploadedText = $uploadedPdf->getText();

        $fileName = $uploadedFile->getClientOriginalName();
        $title = $request->input('title');

        $fingerprints = Fingerprint::all();

        $highestSimilarity = 0;
        $results = [];

        foreach ($fingerprints as $fingerprint) {
            $databasePdf = $parser->parseFile(public_path('uploads/' . $fingerprint->file_dokumen));
            $databaseText = $databasePdf->getText();

            $databaseFingerprint = $this->generateFingerprint($databaseText, $request->input('n'), $request->input('window'), $request->input('prima'));
            $uploadedFingerprint = $this->generateFingerprint($uploadedText, $request->input('n'), $request->input('window'), $request->input('prima'));

            $jaccardCoefficient = $this->calculateJaccardCoefficient($databaseFingerprint, $uploadedFingerprint);

            if ($jaccardCoefficient > $highestSimilarity) {
                $highestSimilarity = $jaccardCoefficient;
            }

            $results[] = [
                'id' => $fingerprint->id, // Ensure you include the ID here
                'judul' => $fingerprint->judul,
                'file_dokumen' => $fingerprint->file_dokumen,
                'jaccardCoefficient' => $jaccardCoefficient,
                'text_content' => $databaseText,
            ];
        }

        foreach ($results as &$result) {
            $result['highlightedTextContent'] = (new FingerprintPdf($result['text_content']))->highlightMatches($uploadedText);
            $result['highlightedUploadedText'] = (new FingerprintPdf($uploadedText))->highlightMatches($result['text_content']);
        }

        // Similarity::create([
        //     'judul' => $title,
        //     'nama_file' => $fileName,
        //     'persentase_kemiripan' => $highestSimilarity,
        // ]);

        Similarity::create([
            'judul' => $request->title,
            'nama_file' => $fileName,
            'persentase_kemiripan' => $highestSimilarity,
            'user_id' => auth()->id(), // ⬅️ tambahkan ini
        ]);

        $threshold = Threshold::first()->value ?? 25;
        return view('plagiarism_pdf.compare', compact('results', 'title', 'fileName', 'uploadedText', 'threshold'));

        // return view('plagiarism_pdf.compare', compact('results', 'title', 'fileName', 'uploadedText'));
    }

    private function generateFingerprint($text, $n, $window, $prima)
    {
        $winnowing = new FingerprintPdf($text);
        $winnowing->SetPrimeNumber($prima);
        $winnowing->SetNGramValue($n);
        $winnowing->SetNWindowValue($window);
        $winnowing->process();

        return $winnowing->GetFingerprintsFirst();
    }

    // private function calculateJaccardCoefficient($fingerprint1, $fingerprint2)
    // {
    //     $arrIntersect = array_intersect($fingerprint1, $fingerprint2);
    //     $arrUnion = array_merge($fingerprint1, $fingerprint2);

    //     $countIntersectFingers = count($arrIntersect);
    //     $countUnionFingers = count($arrUnion);

    //     if ($countUnionFingers > 0) {
    //         $coefficient = $countIntersectFingers / ($countUnionFingers - $countIntersectFingers);
    //         return round(($coefficient * 100), 2);
    //     } else {
    //         return 0;
    //     }
    // }

    private function calculateJaccardCoefficient($fingerprint1, $fingerprint2)
    {
        $arrIntersect = array_unique(array_intersect($fingerprint1, $fingerprint2));
        $arrUnion = array_unique(array_merge($fingerprint1, $fingerprint2));

        if (count($arrUnion) > 0) {
            return round((count($arrIntersect) / count($arrUnion)) * 100, 2);
        } else {
            return 0;
        }
    }

    // private function calculateJaccardCoefficient($fingerprint1, $fingerprint2)
    // {
    //     $arrIntersect = array_intersect($fingerprint1, $fingerprint2);
    //     $arrUnion = array_unique(array_merge($fingerprint1, $fingerprint2));

    //     if (count($arrUnion) > 0) {
    //         return round((count($arrIntersect) / count($arrUnion)) * 100, 2);
    //     } else {
    //         return 0;
    //     }
    // }

    public function destroy($id)
    {
        $similarity = Similarity::findOrFail($id);
        $similarity->delete();

        return redirect()->back()->with('success', 'Data similarity berhasil dihapus.');
    }

    public function edit($id)
    {
        $similarity = Similarity::findOrFail($id);
        return view('plagiarism_pdf.edit', compact('similarity'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         // 'pdf' => 'required|mimes:pdf|max:2048',
    //         'pdf' => 'nullable|mimes:pdf|max:2048', // <- tidak wajib
    //         'n' => 'required|integer',
    //         'window' => 'required|integer',
    //         'prima' => 'required|integer',
    //         'title' => 'required|string',
    //     ]);

    //     $uploadedFile = $request->file('pdf');
    //     $uploadedFile->move(public_path('uploads'), $uploadedFile->getClientOriginalName());

    //     $parser = new \Smalot\PdfParser\Parser();
    //     $uploadedPdf = $parser->parseFile(public_path('uploads/' . $uploadedFile->getClientOriginalName()));
    //     $uploadedText = $uploadedPdf->getText();

    //     $fileName = $uploadedFile->getClientOriginalName();
    //     $title = $request->input('title');

    //     $fingerprints = Fingerprint::all();

    //     $highestSimilarity = 0;
    //     $results = [];

    //     foreach ($fingerprints as $fingerprint) {
    //         $databasePdf = $parser->parseFile(public_path('uploads/' . $fingerprint->file_dokumen));
    //         $databaseText = $databasePdf->getText();

    //         $databaseFingerprint = $this->generateFingerprint($databaseText, $request->input('n'), $request->input('window'), $request->input('prima'));
    //         $uploadedFingerprint = $this->generateFingerprint($uploadedText, $request->input('n'), $request->input('window'), $request->input('prima'));

    //         $jaccardCoefficient = $this->calculateJaccardCoefficient($databaseFingerprint, $uploadedFingerprint);

    //         if ($jaccardCoefficient > $highestSimilarity) {
    //             $highestSimilarity = $jaccardCoefficient;
    //         }

    //         $results[] = [
    //             'judul' => $fingerprint->judul,
    //             'file_dokumen' => $fingerprint->file_dokumen,
    //             'jaccardCoefficient' => $jaccardCoefficient,
    //             'text_content' => $databaseText,
    //         ];
    //     }

    //     foreach ($results as &$result) {
    //         $result['highlightedTextContent'] = (new FingerprintPdf($result['text_content']))->highlightMatches($uploadedText);
    //         $result['highlightedUploadedText'] = (new FingerprintPdf($uploadedText))->highlightMatches($result['text_content']);
    //     }

    //     Similarity::where('id', $id)->update([
    //         'judul' => $title,
    //         'nama_file' => $fileName,
    //         'persentase_kemiripan' => $highestSimilarity,
    //     ]);

    //     $similarity = Similarity::findOrFail($id);

    //     // return view('plagiarism_pdf.edit', compact('similarity', 'results'))->with('success', 'Data similarity berhasil diperbarui.');
    //     return view('plagiarism_pdf.edit', compact('similarity', 'results', 'title', 'fileName'))->with('success', 'Data similarity berhasil diperbarui.');

    // }

    public function update(Request $request, $id)
{
    $request->validate([
        'pdf' => 'nullable|mimes:pdf|max:2048',
        'n' => 'required|integer',
        'window' => 'required|integer',
        'prima' => 'required|integer',
        'title' => 'required|string',
    ]);

    $similarity = Similarity::findOrFail($id);

    $title = $request->input('title');
    $fileName = $similarity->nama_file; // pakai nama file lama by default

    $parser = new \Smalot\PdfParser\Parser();
    $uploadedText = null;

    // Jika ada file baru
    if ($request->hasFile('pdf')) {
        $uploadedFile = $request->file('pdf');
        $fileName = $uploadedFile->getClientOriginalName();
        $uploadedFile->move(public_path('uploads'), $fileName);

        $uploadedPdf = $parser->parseFile(public_path('uploads/' . $fileName));
        $uploadedText = $uploadedPdf->getText();
    } else {
        // Tidak ada file baru, gunakan file lama
        $oldPath = public_path('uploads/' . $fileName);
        $uploadedPdf = $parser->parseFile($oldPath);
        $uploadedText = $uploadedPdf->getText();
    }

    // Fingerprinting
    $fingerprints = Fingerprint::all();
    $highestSimilarity = 0;
    $results = [];

    foreach ($fingerprints as $fingerprint) {
        $databasePdf = $parser->parseFile(public_path('uploads/' . $fingerprint->file_dokumen));
        $databaseText = $databasePdf->getText();

        $databaseFingerprint = $this->generateFingerprint($databaseText, $request->n, $request->window, $request->prima);
        $uploadedFingerprint = $this->generateFingerprint($uploadedText, $request->n, $request->window, $request->prima);

        $jaccardCoefficient = $this->calculateJaccardCoefficient($databaseFingerprint, $uploadedFingerprint);
        if ($jaccardCoefficient > $highestSimilarity) {
            $highestSimilarity = $jaccardCoefficient;
        }

        $results[] = [
            'judul' => $fingerprint->judul,
            'file_dokumen' => $fingerprint->file_dokumen,
            'jaccardCoefficient' => $jaccardCoefficient,
            'text_content' => $databaseText,
        ];
    }

    foreach ($results as &$result) {
        $result['highlightedTextContent'] = (new FingerprintPdf($result['text_content']))->highlightMatches($uploadedText);
        $result['highlightedUploadedText'] = (new FingerprintPdf($uploadedText))->highlightMatches($result['text_content']);
    }

    // Update record
    $similarity->update([
        'judul' => $title,
        'nama_file' => $fileName,
        'persentase_kemiripan' => $highestSimilarity,
    ]);

    // ✅ Ambil nilai threshold dari DB (default 25 jika kosong)
    $threshold = Threshold::first()->value ?? 25;

    // ✅ Tambahkan threshold ke compact agar dikirim ke view
    return view('plagiarism_pdf.edit', compact('similarity', 'results', 'title', 'fileName', 'threshold'))
        ->with('success', 'Data similarity berhasil diperbarui.');

    // return view('plagiarism_pdf.edit', compact('similarity', 'results', 'title', 'fileName'))
    //     ->with('success', 'Data similarity berhasil diperbarui.');
}

}
