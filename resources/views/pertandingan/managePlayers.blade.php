@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <a href="{{ url('pertandingan/'.$pertandingan->id.'/boards') }}"><i class="fa fa-chevron-left text-danger"></i> Pertandingan </a> |
                    <i class="fa fa-calculator"></i>
                    Data Pemain
                </div>
                {!! Form::open(array('url' => $formUrl, 'method'=>$formMethod, 'files' => true, 'id'=>'form-pertandingan')) !!}
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nama Pasangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pertandingan->players as $key => $player)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <div class="form-group">
                                                {!! Form::text('nama_player['.$player->id.']', $player->nama_player, ['class'=>'form-control nama-player', 'id'=>'nama_player_'.$player->id]) !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-danger btn-cancel-form"> <i class="fa fa-times"></i> Batal</button>
                        <button type="button" class="btn btn-primary btn-submit-form float-right"> <i class="fa fa-save"></i> Submit</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection()

@section('script')
    <script type="text/javascript">
        var handleForm = function(){
            var cancelForm = function(){
                $('.btn-cancel-form').click(function(){
                    y = confirm("Batalkan perubahan pada form");
                    if(y){
                        window.location.replace('{{ url("pertandingan/".$pertandingan->id."/boards") }}');
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
                        },
                        nama: {
                            required: true,
                            minlength: 3
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

                $.validator.addMethod("nRequired", $.validator.methods.required, "Nama Pasangan tidak boleh kosong");

                $.validator.addClassRules('nama-player',{
                    nRequired: true
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
                }
            }
        }()

        $(document).ready(function(){
            handleForm.init();
        })
    </script>
@endsection()
