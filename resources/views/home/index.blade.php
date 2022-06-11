@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pertandingan</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm btn-flat">Buat Pertandingan Baru</button>
                    </div>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Pertandingan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pertandingans as $i => $pertandingan)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $pertandingan->nama_pertandingan }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm btn-flat" href="{{ url('pertandingan/'.$pertandingan->id.'/edit') }}"> <i class="fa fa-check"></i> Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection