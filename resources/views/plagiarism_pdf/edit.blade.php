@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2" style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">Deteksi Kemiripan Proposal</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Proposal</a></li>
                        <li class="breadcrumb-item active">Deteksi</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <p>Menghitung Nilai Similarity</p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Update Proposal</div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <!-- Form untuk mengupdate dokumen -->
                                <form action="{{ route('plagiarism.update', ['id' => $similarity->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Bagian form untuk mengupdate judul -->
                                    <div class="form-group">
                                        <label for="title">Judul:</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ $similarity->judul }}" required>
                                    </div>

                                    <!-- Bagian form untuk mengupdate file proposal -->
                                    <!-- <div class="form-group">
                                        <label for="pdf">File Proposal:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-file-pdf fa-sm"></i>
                                                </div>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="pdf" name="pdf" class="custom-file-input" onchange="displayFileName(this)">
                                                <label class="custom-file-label text-truncate" for="file" data-browse="Upload">Upload file proposal dengan format pdf</label>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- Bagian form untuk mengupdate file proposal -->
                                    <div class="form-group">
                                        <label for="pdf">File Proposal:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-file-pdf fa-sm"></i>
                                                </div>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="pdf" name="pdf" class="custom-file-input" onchange="displayFileName(this)">
                                                <label class="custom-file-label text-truncate" for="pdf" data-browse="Upload">
                                                    {{ $similarity->nama_file ?? 'Upload file proposal dengan format pdf' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form untuk parameter n-gram, window size, dan prime number -->
                                    <div class="form-group" style="display: none;">
                                        <label for="n">N Gram Value:</label>
                                        <input type="number" class="form-control" id="n" name="n" value="8" required>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label for="window">Window Size:</label>
                                        <input type="number" class="form-control" id="window" name="window" value="6" required>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <label for="prima">Prime Number:</label>
                                        <input type="number" class="form-control" id="prima" name="prima" value="23" required>
                                    </div>

                                    <!-- Tombol untuk submit form -->
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('plagiarism.index') }}" class="btn btn-secondary mr-2">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                        <button type="submit" id="updateButton" class="btn btn-primary">Update Proposal</button>
                                    </div>
                                </form>

                        </div>
                    </div>
                </div>
            </div>

            @if(isset($title) && isset($fileName))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Document Information</div>
                        <div class="card-body">
                            <p><strong>Judul :</strong> {{ $title }}</p>
                            <!-- <p><strong>Nama Dokumen :</strong> {{ $fileName }}</p> -->
                            <p>
                                <strong>Nama Dokumen :</strong>
                                <a href="{{ asset('uploads/' . $fileName) }}" download>{{ $fileName }}</a>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            @endif


            @if(isset($results))
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">Hasil Perbandingan</div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="data-similarity" class="table table-bordered table-striped">
                                    <!-- <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Title</th>
                                            <th>Document</th>
                                            <th>Similarity (%)</th>
                                        </tr>
                                    </thead> -->
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Title</th>
                                            <th>Document</th>
                                            <th>Similarity (%)</th>
                                            <th>Detail Proposal Latih</th>    {{-- Tambahkan ini --}}
                                            <th>Detail Proposal Uji</th>      {{-- Tambahkan ini --}}
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{-- Urutkan hasil berdasarkan similarity secara descending --}}
                                        @php
                                            $sortedResults = collect($results)->sortByDesc('jaccardCoefficient');
                                        @endphp

                                        @foreach ($sortedResults as $result)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $result['judul'] }}</td>
                                            <!-- <td>{{ $result['file_dokumen'] }}</td> -->
                                            <td class="text-center">
                                            <a href="{{ asset('uploads/' . $result['file_dokumen']) }}" download title="Download Dokumen">
                                                <i class="fas fa-eye text-primary" style="cursor: pointer;"></i>
                                            </a>
                                            </td>
                                            <!-- <td>{{ $result['jaccardCoefficient'] }}%</td> -->
                                            <td>{{ $result['jaccardCoefficient'] }}%</td>
                                            <td>
                                                <div class="text-content" style="max-height: 100px; overflow: hidden;">
                                                    {!! nl2br($result['highlightedTextContent']) !!}
                                                </div>
                                                <button class="btn btn-info btn-sm" onclick="toggleText(this)">Show More</button>
                                            </td>
                                            <td>
                                                <div class="text-content" style="max-height: 100px; overflow: hidden;">
                                                    {!! nl2br($result['highlightedUploadedText']) !!}
                                                </div>
                                                <button class="btn btn-info btn-sm" onclick="toggleText(this)">Show More</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modal-default1" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Peringatan!</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- <div class="modal-body">
                                Maaf, Judul Skripsi Anda telah ditolak karena mempunyai banyak kesamaan dengan Judul Skripsi lainnya.
                            </div> -->
                            <div class="modal-body">
                                Maaf, Judul Skripsi Anda telah ditolak karena mempunyai tingkat kemiripan di atas {{ $threshold }}%.
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

                <!-- Modal -->
                <div class="modal fade" id="modal-default2" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Peringatan!</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- <div class="modal-body">
                                Selamat, Judul Skripsi Anda telah disetujui
                            </div> -->
                            <div class="modal-body">
                                Selamat, Judul Skripsi Anda telah disetujui karena mempunyai tingkat kemiripan di bawah atau sama dengan {{ $threshold }}%.
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
            @endif



        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection

<!-- Script JavaScript -->
@section('scripts')
<script>
    const threshold = {{ $threshold ?? 25 }};
</script>

<script>
    function displayFileName(input) {
        const fileName = input.files[0].name;
        const label = input.nextElementSibling;
        label.innerText = fileName;
    }
</script>

<!-- Script JavaScript -->
<script>
    $(function () {
        // Memeriksa apakah tabel ada sebelum menjalankan script
        if ($('#data-similarity').length > 0) {
            $("#data-similarity").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }
    });
</script>

<script>
    $(document).ready(function () {
        // Fungsi untuk mendapatkan nilai persentase kemiripan dari tabel
        function getSimilarityPercentage() {
            var similarityPercentage = 0;
            $('#data-similarity tbody tr').each(function () {
                // Ambil nilai persentase kemiripan dari setiap baris tabel
                var percentage = parseInt($(this).find('td:last').text().replace('%', ''));
                // Jika nilai persentase kemiripan lebih besar dari nilai sebelumnya, update nilai similarityPercentage
                if (percentage > similarityPercentage) {
                    similarityPercentage = percentage;
                }
            });
            return similarityPercentage;
        }

        // Tangkap tombol deteksi kemiripan
        var deteksiButton = $('#deteksiButton');

        // Fungsi untuk menampilkan modal sesuai dengan similarity
        function tampilkanModal(similarityPercentage) {
            if (similarityPercentage > threshold) {
                // Jika similarity > 25%, tampilkan modal-default1
                $('#modal-default1').modal('show');
                // Sembunyikan modal-default2 jika sedang tampil
                $('#modal-default2').modal('hide');
            } else {
                // Jika similarity <= 25%, tampilkan modal-default2
                $('#modal-default1').modal('hide');
                $('#modal-default2').modal('show');
            }
        }

        // Pemanggilan fungsi saat dokumen pertama kali dimuat jika similarity sudah tersedia
        var similarityPercentage = getSimilarityPercentage();
        tampilkanModal(similarityPercentage);
    });
</script>

<script>
    function toggleText(button) {
        const textContent = button.previousElementSibling;
        if (textContent.style.maxHeight === 'none') {
            textContent.style.maxHeight = '100px';
            button.innerText = 'Show More';
        } else {
            textContent.style.maxHeight = 'none';
            button.innerText = 'Show Less';
        }
    }
</script>

@endsection
