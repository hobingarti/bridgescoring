@extends('app')

@section('style')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                <a href="{{ url('pertandingan/') }}"><i class="fa fa-chevron-left text-danger"></i> Pertandingan </a> |
                    <i class="fa fa-calculator"></i>
                    Data Pertandingan
                </div>
                {!! Form::open(array('url' => $formUrl, 'method'=>$formMethod, 'files' => true, 'id'=>'form-pertandingan')) !!}
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nama_pertandingan">Nama Pertandingan</label>
                                {!! Form::text('nama_pertandingan', $pertandingan->nama_pertandingan, ['id'=>'nama_pertandingan', 'class'=>'form-control', 'required'=>"required"]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nama_pertandingan">Tanggal</label>
                                {!! Form::text('tanggal', $pertandingan->tanggal, ['id'=>'tanggal', 'class'=>'form-control datepicker', 'required'=>"required"]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jumlah_pasangan">Jumlah Pasangan</label>
                                {!! Form::number('jumlah_pasangan', $pertandingan->jumlah_pasangan, ['id'=>'jumlah_pasangan', 'class'=>'form-control', 'required'=>'required', 'disabled'=>($pertandingan->id != '' ? true : false)]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jumlah_board">Jumlah Board</label>
                                {!! Form::number('jumlah_board', $pertandingan->jumlah_board, ['id'=>'jumlah_board', 'class'=>'form-control', 'required'=>'required', 'disabled'=>($pertandingan->id != '' ? true : false)]) !!}
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

            var validateForm = function(){
                $('#form-pertandingan').validate({
                    rules:{
                        nama_pertandingan: {
                            required: true
                        },
                        jumlah_board: {
                            required: true,
                            number: true
                        },
                        jumlah_pasangan: {
                            required: true,
                            number: true
                        }
                    },
                    messages: {
                        nama_pertandingan: {
                            required: 'Tidak boleh kosong'
                        },
                        jumlah_board: {
                            required: 'Tidak boleh kosong',
                            number: 'Input harus berupa angka'
                        },
                        jumlah_pasangan: {
                            required: 'Tidak boleh kosong',
                            number: 'Input harus berupa angka'
                        }
                    },
                    errorElement: 'span',
                        errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                })
            }

            var submitForm = function(){
                $('.btn-submit-form').click(function(){
                    if($('#form-pertandingan').valid()){
                        $("#form-pertandingan").submit();
                    }else{
                        swal.fire('Tidak bisa menyimpan data', 'Silahkan Lengkapi isian form terlebih dahulu!', 'error');
                    }
                })
            }

            return{
                init: function(){
                    validateForm();
                    cancelForm();
                    submitForm();

                    $(".datepicker").daterangepicker({
                        locale: {
                            format: 'DD/MM/YYYY'
                        },
                        format: 'DD/MM/YYYY',
                        autoclose: true,
                        todayBtn: false,
                        timePicker: false,
                        timePicker24Hour: true,
                        singleDatePicker: true, //<==HERE
                    });
                }
            }
        }()

        $(document).ready(function(){
            handleForm.init();
        })
    </script>
@endsection