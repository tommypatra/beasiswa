@extends('admin.web')

@section('head')
    <link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="plugins/validationengine/css/validationEngine.jquery.css" rel="stylesheet">
@endsection

@section('container')

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Ruang Ujian Seleksi</h6>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-12 mb-3">

                        <div class="row input-group input-group-outline">
                            <div class="col-sm-4">
                                <label class="col-form-label">Tahun</label>
                                <select class="form-control mb-3" name="tahun" id="tahun">                                
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label class="col-form-label">Beasiswa</label>
                                <select class="form-control" name="beasiswa_id" id="beasiswa_id">                                
                                </select>
                            </div>
                        </div>
    
                    </div>  
                </div>                
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-12">

                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="cekSemua"></th>
                                        <th>No</th>
                                        <th>Ruangan</th>
                                        <th>Penguji</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button class="btn btn-danger hapusTerpilih"><span class="material-icons">delete_forever</span> Hapus Terpilih</button>
                        </div>
                                    

                    </div>  
                </div>                
            </div>
        </div>
    </div>
</div>

<!-- MULAI MODAL -->
<div class="modal fade modal-lg" id="modal-ruang-beasiswa" role="dialog">
    <div class="modal-dialog">
        <form id="fruangbeasiswa">
            <input type="hidden" name="id" id="id-ruang-beasiswa">
            <input type="hidden" name="insertpegawai" id="insert-pegawai" value="0">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RUANGAN UJIAN SELEKSI</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">

                    <div class="row input-group input-group-outline">
                        <div class="col-sm-8">
                            <label class="col-form-label">Ruang</label>
                            <select class="form-control validate[required]" name="ruang_id" id="ruang_id">                                
                            </select>
                        </div>
                    </div>

                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Penguji</label>
                        <div class="col-sm-12">
                            <select class="form-control validate[required]" name="pegawai_id[]" id="pegawai_id">                                
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

@endsection

@section("scriptJs")
    <script src='plugins/bootstrap-material-moment/moment.js'></script>
    <script src='plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'></script>
    <script src='plugins/validationengine/js/jquery.validationEngine.js'></script>
    <script src='plugins/validationengine/js/languages/jquery.validationEngine-id.js'></script>
    <script src="plugins/datatables/datatables.min.js"></script>
    <script src="plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        sel2_datalokal("#ruang_id");
        sel2_datalokal("#pegawai_id",{},null,false,true);
        sel2_datalokal("#beasiswa_id");
        sel2_datalokal("#tahun");

        //mengecek semua ceklist
        $(".cekSemua").change(function () {
            $(".cekbaris").prop('checked', $(this).prop("checked"));
        });

        $('.datepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
        });

        function cekPilihBeasiswa(){
            if($("#beasiswa_id").val()===null || $("#beasiswa_id").val()===""){
                alert("pilih beasiswa terlebih dahulu!");
                die();
            }
        }

        init();
        function init() {
            //untuk tahun beasiswa
            let formVal={_token:$("meta[name='csrf-token']").attr("content")};
            appAjax('{{ route("syarat-init") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    let vElement="#tahun";
                    let data=vRet.data.beasiswa;
                    $(vElement).empty();
                    if(data.length>0){
                        $(vElement).append($('<option>', {value:"", text: "- pilih -"}));
                        $.each(data, function( key, dp ) {
                            $(vElement).append($('<option>', {value:dp.tahun, text: dp.tahun}));
                        });
                    }                    
                }
            });

            appAjax('{{ route("ruang-ujian-init") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    //untuk ruang
                    let data=vRet.data.ruang;
                    let vElement="#ruang_id"; 
                    $(vElement).empty();
                    if(data.length>0){
                        $(vElement).append($('<option>', {value:"", text: "- pilih -"}));
                        $.each(data, function( key, dp ) {
                            $(vElement).append($('<option>', {value:dp.id, text: dp.ruang}));
                        });
                    }                    

                    //untuk pegawai
                    data=vRet.data.pegawai;
                    vElement="#pegawai_id"; 
                    $(vElement).empty();
                    if(data.length>0){
                        $.each(data, function( key, dp ) {
                            $(vElement).append($('<option>', {value:dp.id, text: dp.user.nama}));
                        });
                    }                    

                }
            });

        };

        $("#tahun").change(function(){
            cariBeasiswa($(this).val());
        })

        $("#beasiswa_id").change(function(){
            refresh();
        })

        function cariBeasiswa(vTahun){
            var formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                cari:{
                    0:{srchFld:'tahun',srchVal:vTahun},
                },
            };
            let vElement="#beasiswa_id";
            $(vElement).empty();
            appAjax("{{ route('beasiswa-search') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    let data=vRet.data;
                    if(data.length>0){
                        $(vElement).append($('<option>', {value:"", text: "- pilih -"}));
                        $.each(data, function( key, dp ) {
                            $(vElement).append($('<option>', {value:dp.id, text: dp.nama}));
                        });
                    }                    
                }
            });
        }

        var dtTable = $('.datatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [25, 50, 75, -1],
                ["25", "50", "75", "Semua"]
            ],
            ajax: {
                url: "{{ route('ruang-ujian-read') }}",
                dataType: "json",
                type: "POST",
                data: function (d) {
                    d._token = $("meta[name='csrf-token']").attr("content");  
                    d.beasiswa_id = $("#beasiswa_id").val();  
                },
                dataSrc: function (json) {
                    return json.data;
                },
            },
            "order": [
                [2, "asc"],
            ],
            dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
            language: {
                'paginate': {
                    'previous': '<span class="material-icons">navigate_before</span>',
                    'next': '<span class="material-icons">navigate_next</span>'
                }
            },
            buttons: [
                {
                    text: '<span class="material-icons">add_circle_outline</span>',
                    className: 'btn btn-secondary btn-sm',
                    action: function ( e, dt, node, config ) {
                        tambah();
                    }                
                },
                {
                    text: '<span class="material-icons">refresh</span>',
                    className: 'btn btn-secondary btn-sm',
                    action: function ( e, dt, node, config ) {
                        refresh();
                    }                
                },
            ],
            columns: [
                {data: 'cek',className: "text-center", width:"5%", orderable: false, searchable: false},
                {data: 'no', width:"5%",searchable: false},
                {data: 'ruangujian', width:"40%",},
                {data: 'penguji', width:"30%"},
                {data: 'action', width:"5%",className: "text-center", orderable: false, searchable: false},
            ],
            initComplete: function (e) {
                var api = this.api();
                $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function (e) {
                    if (e.keyCode == 13) {
                        api.search(this.value).draw();
                    }
                });
            },
        });
        dtTable.on('order.dt search.dt', function () {
            let i = 1;
            dtTable.cells(null, 1, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();

        function refresh(){
            if (dtTable)
                $('.datatable').DataTable().ajax.reload(null, false);
        }

        function resetForm(){
            $('#fruangbeasiswa')[0].reset();
            $('#id-ruang-beasiswa').val("");
            $('#ruang_id').val("").trigger('change');
            $('#pegawai_id').val("").trigger('change');
            $('#pegawai_id').prop('disabled', false);
        };

        function fillform(vdt){
            let dt=vdt[0];
            $('#id-ruang-beasiswa').val(dt.id);
            $('#ruang_id').val(dt.ruang.id).trigger('change');
            let data=dt.ruang_penguji;
            let pilihpegawai=[];
            if(data.length>0){
                $('#insert-pegawai').val('0')
                $('#pegawai_id').prop('disabled', true);
                $.each(data, function( key, dp ) {
                    pilihpegawai.push(dp.pegawai_id);
                });
            }else{
                $('#insert-pegawai').val('1')
                $('#pegawai_id').prop('disabled', false);
            }
            $('#pegawai_id').val(pilihpegawai).trigger('change');

            
        }

        function tambah(){
            cekPilihBeasiswa();
            resetForm();
            $('#insert-pegawai').val('1');
            var myModal1 = new bootstrap.Modal(document.getElementById('modal-ruang-beasiswa'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal1.toggle();
        }

        $("#fruangbeasiswa").submit(function(e) {
            e.preventDefault();
            tinymce.triggerSave();
            let formVal = $(this).serializeArray();
            formVal.push({name:"_token",value:$("meta[name='csrf-token']").attr("content")});
            formVal.push({name:"beasiswa_id",value:$("#beasiswa_id").val()});
            if($(this).validationEngine('validate')){
                appAjax('{{ route("ruang-ujian-save") }}', $.param(formVal)).done(function(vRet) {
                    //resetForm();
                    refresh();
                    if(vRet.status){
                        $('#id-ruang-beasiswa').val(vRet.id);
                        tutupModal();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });

        //hapus pegawai
        $(document).on("click",".btn-hapus-pegawai",function(){
            if(confirm("apakah anda yakin?")){
                var formVal={_token:$("meta[name='csrf-token']").attr("content"),id:$(this).data("id")};
                appAjax("{{ route('ruang-ujian-delete-pegawai') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        refresh();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });                
            }
        });

        function tutupModal() {
            const myModal = document.querySelector('#modal-ruang-beasiswa');
            const modal = bootstrap.Modal.getInstance(myModal);    
            modal.hide();
        }

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetForm();
            var formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                cari:{
                    0:{srchVal:$(this).data("id")},
                },
            };
            appAjax("{{ route('ruang-ujian-search') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    var myModal = new bootstrap.Modal(document.getElementById('modal-ruang-beasiswa'), {
                        backdrop: 'static',
                        keyboard: false,
                    });
                    myModal.toggle();
                    fillform(vRet.data);
                }else{
                    showmymessage(vRet.messages,vRet.status);
                }
            });
        })

        //hapus
        function hapus(idTerpilih){
            var formVal={_token:$("meta[name='csrf-token']").attr("content"),id:idTerpilih};
            if(idTerpilih.length > 0 && confirm("apakah anda yakin?")){
                appAjax("{{ route('ruang-ujian-delete') }}", formVal).done(function(vRet) {
                    if(vRet.status){
                        refresh();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });                
            }
        }

        //tombol btn-hapus dari datatables
        $(document).on("click",".btn-hapus",function(){
            hapus([$(this).data("id")]);
        })

        //menghapus banyak data dari ceklist datatables 	
        $(".hapusTerpilih").click(function () {
            let idTerpilih = [];
            $('.cekbaris').each(function (i) {
                if ($(this).is(':checked')) {
                    idTerpilih.push($(this).val());
                }
            });                
            hapus(idTerpilih);
        })
        //akhir hapus      


    </script>
@endsection
