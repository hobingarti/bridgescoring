@extends('app')

@section('style')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <i class="fa fa-calculator"></i>
                    Data Pertandingan
                </div>
                {!! Form::open(array('url' => $formUrl, 'method'=>$formMethod, 'files' => true, 'id'=>'form-pertandingan')) !!}
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="nama_pertandingan">Nama Pertandingan</label>
                                {!! Form::text('nama_pertandingan', $pertandingan->nama_pertandingan, ['id'=>'nama_pertandingan', 'class'=>'form-control', 'required'=>"required"]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jumlah_pasangan">Jumlah Pasangan</label>
                                {!! Form::number('jumlah_pasangan', $pertandingan->jumlah_pasangan, ['id'=>'jumlah_pasangan', 'class'=>'form-control', 'required'=>'required']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jumlah_board">Jumlah Board</label>
                                {!! Form::number('jumlah_board', $pertandingan->jumlah_board, ['id'=>'jumlah_board', 'class'=>'form-control', 'required'=>'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-danger btn-cancel-form"> <i class="fa fa-times"></i> Batal</button>
                        <button type="button" class="btn btn-primary btn-submit-form float-right"> <i class="fa fa-save"></i> Submit</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var handleForm = function(){
            var cancelForm = function(){
                $('.btn-cancel-form').click(function(){
                    y = confirm("Batalkan perubahan pada form");
                    if(y){
                        window.location.replace('{{ url("pertandingan") }}');
                    }
                })
            }

            var submitForm = function(){
                $('.btn-submit-form').click(function(){
                    $('#form-pertandingan').submit();
                })
            }

            return{
                init: function(){
                    cancelForm();
                    submitForm();
                }
            }
        }()

        $(document).ready(function(){
            handleForm.init();
        })
    </script>
@endsection