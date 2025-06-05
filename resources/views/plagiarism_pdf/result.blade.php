<!-- resources/views/plagiarism_pdf/result.blade.php -->

@extends('layout.main')

@section('content')
  <div class="content-wrapper px-4 py-2 " style="min-height: 485px;">
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
              <li class="breadcrumb-item active">Deteksi Proposal</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">Comparison Results</div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="data-proposal" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Jaccard Coefficient</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td>{{ $result['file_dokumen'] }}</td>
                                            <td>{{ $result['jaccardCoefficient'] }}</td>
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
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    </div>
@endsection

<!-- Script JavaScript -->
@section('scripts')
<script>
    $(document).ready(function () {
        $("#data-proposal").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            "language": {
                "emptyTable": "No data available in table"
            }
        });
    });
</script>
@endsection


