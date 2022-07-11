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
                    <table class="table table-bordered datatable" id="dTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Pertandingan</th>
                                <th>Jumlah Pasangan</th>
                                <th>Jumlah Board</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger btn-cancel-form"> <i class="fa fa-times"></i> Batal</button>
                    <button type="button" class="btn btn-primary btn-submit-form float-right"> <i class="fa fa-save"></i> Submit</button>
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
                        url: '{{url("kuisioner/list_kuisioner")}}',
                        data:function(d){
                                d.nama_kuisioner = $("#q_nama_kuisioner").val();
                                d.id_jenis_kuisioner = $("#q_id_jenis_kuisioner").val();
                                d.id_tahun_ajar = $("#q_id_tahun_ajar").val();
                            }
                    },
                columns: [
                    {data: 'no', name: 'id',width:"2%"},
                    {data: 'id_tahun_ajar', name: 'id_tahun_ajar'},
                    {data: 'nama_kuisioner', name: 'nama_kuisioner'},
                    {data: 'nama_jenis_kuisioner', name: 'nama_jenis_kuisioner'},
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
