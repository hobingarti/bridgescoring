@extends('app')

@section('style')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> <a href="{{ url('pertandingan/'.$pertandingan->id.'/boards') }}"><i class="fa fa-chevron-left text-danger"></i> Pertandingan </a> | Ranking</h3>
                </div>

                <div class="card-body p-0" style="overflow:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 200px;">Player</th>
                                <th style="width: 100px;">Total Score</th>
                                @foreach($arrBoards as $keyB => $board)
                                    <th style="width: 100px;">{{ $keyB }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($arrPlayers as $keyP => $player)
                                <tr>
                                    <td>{{ $keyP }}.</td>
                                    <td>{{ $player['player']->nama_player }}</td>
                                    <td><span class="badge bg-success">{{ $player['total_score'] }}</span></td>
                                    @foreach($player['boards'] as $keyB => $pointBoard)
                                        <td>
                                            @if($pointBoard === '-')
                                                <span class="badge bg-secondary">-</span>
                                            @else
                                                <span class="badge bg-primary">{{ $pointBoard }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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