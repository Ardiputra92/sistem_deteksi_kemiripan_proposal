@extends('layout.main')
@section('content')
<div class="content-wrapper px-4 py-2" style="min-height: 485px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">Deteksi Kemiripan Proposal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{asset('template')}}/#">Proposal</a></li>
                        <li class="breadcrumb-item active">Deteksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <p>Menghitung Nilai Similarity</p>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Form Proposal</div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <form action="{{ route('plagiarism.compare') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Judul:</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan judul proposal" required>
                                </div>

                                <div class="form-group">
                                    <label for="pdf">File Proposal:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-file-pdf fa-sm"></i>
                                            </div>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" id="pdf" name="pdf" class="custom-file-input"
                                                onchange="displayFileName(this)">
                                            <label class="custom-file-label text-truncate" for="file"
                                                data-browse="Upload">Upload file proposal dengan format pdf</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="display: none;">
                                    <label for="n">N Gram Value:</label>
                                    <input type="number" class="form-control" id="n" name="n" value="8" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label for="window">Window Size:</label>
                                    <input type="number" class="form-control" id="window" name="window" value="6"
                                        required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <label for="prima">Prime Number:</label>
                                    <input type="number" class="form-control" id="prima" name="prima" value="23"
                                        required>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('plagiarism.index') }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" id="deteksiButton" class="btn btn-primary">Deteksi
                                        Kemiripan</button>
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
                        <div class="card-header">Informasi Dokumen</div>
                        <div class="card-body">
                            <p><strong>Judul :</strong> {{ $title }}</p>
                            <!-- <p><strong>Nama Dokumen :</strong> {{ $fileName }}</p> -->
                            <p>
                                <strong>Nama Dokumen :</strong>
                                <a href="{{ asset('uploads/' . $fileName) }}" download title="Download Dokumen">
                                    {{ $fileName }}
                                </a>
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
                        <div class="card-body">
                            <table id="data-similarity" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Dokumen</th>
                                        <th>Similarity (%)</th>
                                        <th>Detail Proposal Latih</th>
                                        <th>Detail Proposal Uji</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                    </div>
                </div>
            </div>

            <!-- Modal untuk Peringatan -->
            <div class="modal fade" id="modal-default1" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Peringatan!</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Maaf, Judul Skripsi Anda telah ditolak karena mempunyai tingkat kemiripan di atas {{ $threshold }}%.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Persetujuan -->
            <div class="modal fade" id="modal-default2" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Peringatan!</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Selamat, Judul Skripsi Anda telah disetujui karena mempunyai tingkat kemiripan di bawah atau sama dengan {{ $threshold }}%
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const threshold = {{ $threshold ?? 25 }};

    function displayFileName(input) {
        const fileName = input.files[0].name;
        const label = input.nextElementSibling;
        label.innerText = fileName;
    }

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

    $(document).ready(function () {
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

        function getSimilarityPercentage() {
            var similarityPercentage = 0;
            $('#data-similarity tbody tr').each(function () {
                var percentage = parseFloat($(this).find('td:nth-child(4)').text().replace('%', ''));
                if (percentage > similarityPercentage) {
                    similarityPercentage = percentage;
                }
            });
            return similarityPercentage;
        }

        function tampilkanModal(similarityPercentage) {
            if (similarityPercentage > threshold) {
                $('#modal-default1').modal('show');
                $('#modal-default2').modal('hide');
            } else {
                $('#modal-default1').modal('hide');
                $('#modal-default2').modal('show');
            }
        }

        var similarityPercentage = getSimilarityPercentage();
        tampilkanModal(similarityPercentage);
    });
</script>
@endsection

<!-- @section('scripts')
<script>
    function displayFileName(input) {
        const fileName = input.files[0].name;
        const label = input.nextElementSibling;
        label.innerText = fileName;
    }

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

    $(document).ready(function () {
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

        // Display modal based on similarity percentage
        function getSimilarityPercentage() {
            var similarityPercentage = 0;
            $('#data-similarity tbody tr').each(function () {
                var percentage = parseInt($(this).find('td:nth-child(4)').text().replace('%', ''));
                if (percentage > similarityPercentage) {
                    similarityPercentage = percentage;
                }
            });
            return similarityPercentage;
        }

        function tampilkanModal(similarityPercentage) {
            if (similarityPercentage > 25) {
                $('#modal-default1').modal('show');
                $('#modal-default2').modal('hide');
            } else {
                $('#modal-default1').modal('hide');
                $('#modal-default2').modal('show');
            }
        }

        var similarityPercentage = getSimilarityPercentage();
        tampilkanModal(similarityPercentage);
    });
</script>
@endsection -->
