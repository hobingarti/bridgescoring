@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <i class="fa fa-calculator"></i>
                    Data Pertandingan
                </div>
                <div class="card-body">
                    Daftar Board
                </div>
                <div class="card-footer">
                    
                </div>
            </div>
        </div>
    </div>
@endsection()

@section('script')
    <script type="text/javascript">
        var handleDataTable = function(){
            var dTable = $("#dTable").dataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax:{
                        url: '{{url("pertandingan/listPertandingan")}}',
                        data:function(d){
                                // d.nama_kuisioner = $("#q_nama_kuisioner").val();
                            }
                    },
                columns: [
                    {data: 'no', name: 'id', width:"2%", searchable: false},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'nama_pertandingan', name: 'nama_pertandingan'},
                    {data: 'jumlah_pasangan', name: 'jumlah_pasangan'},
                    {data: 'jumlah_board', name: 'jumlah_board'},
                    {data: 'action', name: 'id',orderable: false, searchable: false, class:'text-center'}
                ],
                scrollX: true,
                stateSaveCallback: function(settings,data) {
                    localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                },
                drawCallback: function( settings ) {
                    var api = this.api();
                }
            })

            return{
                init: function(){
                    
                }
            }
        }();

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
            handleDataTable.init();
            handleForm.init();
        })
    </script>
@endsection()
