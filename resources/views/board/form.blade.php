@extends('app')

@section('style')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> <a href="{{ url('pertandingan/'.$board->id_pertandingan.'/boards') }}"><i class="fa fa-chevron-left text-danger"></i> Pertandingan : {{ $board->pertandingan->nama_pertandingan }} </a> | Board {{$board->nomor_board}}</h3>
                </div>

                {!! Form::open(array('url' => $formUrl, 'method'=>$formMethod, 'files' => true, 'id'=>'form-board')) !!}
                    <div class="card-body p-0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th style="width: 150px;"># NS</th>
                                    <th style="width: 150px;"># EW</th>
                                    <th style="width: 150px;">Score NS</th>
                                    <th>Point NS</th>
                                    <th>Point EW</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matchs as $i => $match)
                                    <tr>
                                        <td>{{ $i+1 }}.</td>
                                        <td>
                                            {!! Form::hidden('match['.$i.'][id]', $match->id) !!}
                                            {!! Form::select('match['.$i.'][id_pemain_ns]', $players, $match->id_pemain_ns, ['class'=>'form-control id_pemain']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select('match['.$i.'][id_pemain_ew]', $players, $match->id_pemain_ew, ['class'=>'form-control id_pemain']) !!}
                                        </td>
                                        <td>
                                            {!! Form::number('match['.$i.'][score_ns]', $match->score_ns, ['class'=>'form-control score_ns']) !!}
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $match->point_ns }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $match->point_ew }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                {!! Form::close() !!}
                <div class="card-footer text-center">
                    <a href="{{ url('board/'.$prevBoard.'/edit') }}" class="btn btn-primary">Prev</a>
                    <button type="submit" class="btn btn-primary btn-submit-form">Simpan</button>
                    <a href="{{ url('board/'.$nextBoard.'/edit') }}" class="btn btn-primary">Next</a>
                </div>
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
                        window.location.replace('{{ url("pertandingan/".$pertandingan->id."/boards") }}');
                    }
                })
            }

            var validateForm = function(){
                $('#form-board').validate({
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
                    if($('#form-board').valid()){
                        $("#form-board").submit();
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