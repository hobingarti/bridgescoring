@extends('app')

@section('style')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Simple Full Width Table</h3>
                </div>

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
                            <tr>
                                <td>1.</td>
                                <td>
                                    <input type="number" name="test" id="test" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="test" id="test" class="form-control">
                                </td>
                                <td>
                                    <input type="number" name="test" id="test" class="form-control">
                                </td>
                                <td>
                                    <span class="badge bg-danger">55%</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">55%</span>
                                </td>
                            </tr>
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