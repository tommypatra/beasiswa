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
                    <h6 class="text-white text-capitalize ps-3">Dokumen Syarat Beasiswa</h6>
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
                                        <th>Ruang</th>
                                        <th>Penguji</th>
                                        <th>Mahasiswa</th>
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
<div class="modal fade modal-lg" id="modal-pembagian-otomatis" role="dialog">
    <div class="modal-dialog">
        <form id="fpembagianotomatis">
            <input type="hidden" name="id" id="id-syarat">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM PEMBAGIAN RUANGAN OTOMATIS</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">

                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Nama Syarat</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control validate[required]" name="nama" id="nama" placeholder="syarat">
                        </div>
                    </div>

                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea rows="4" class="form-control" name="keterangan" id="keterangan" ></textarea>
                        </div>
                    </div>

                    <div class="row input-group input-group-outline">
                        <div class="col-sm-4">
                            <label class="col-form-label">Wajib</label>
                            <select class="form-control validate[required] mb-3" name="wajib" id="wajib">                                
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-form-label">Aktif</label>
                            <select class="form-control validate[required]" name="aktif" id="aktif">                                
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
        sel2_aktif2("#aktif");
        sel2_aktif1("#wajib");
        sel2_datalokal("#tahun");
        sel2_datalokal("#beasiswa_id");

        //mengecek semua ceklist
        $(".cekSemua").change(function () {
            $(".cekbaris").prop('checked', $(this).prop("checked"));
        });

        $('.datepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
        });

        $("#tahun").change(function(){
            cariBeasiswa($(this).val());
        })

        $("#beasiswa_id").change(function(){
            refresh();
        })

        function cekPilihBeasiswa(){
            if($("#beasiswa_id").val()===null || $("#beasiswa_id").val()===""){
                alert("pilih beasiswa terlebih dahulu!");
                die();
            }
        }

        init();
        function init() {
            let formVal={_token:$("meta[name='csrf-token']").attr("content")};
            let vElement="#tahun";
            appAjax('{{ route("syarat-init") }}', formVal).done(function(vRet) {
                if(vRet.status){
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
        };

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
                url: "{{ route('ruangpeserta-read') }}",
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
                // [2, "asc"],
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
                    text: '<span class="material-icons">domain</span>',
                    className: 'btn btn-secondary btn-sm',
                    action: function ( e, dt, node, config ) {
                        cekPilihBeasiswa();
                        let id=$("#beasiswa_id").val();
                        let url='{{ route("ruangpeserta-fpembagian",[":id"]) }}';
                        url = url.replace(':id', id);
                        window.location.href = url;
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
                {data: 'ruang', width:"25%",},
                {data: 'penguji', width:"30%",},
                {data: 'mahasiswa', width:"30%"},
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
            $('#fbeasiswa')[0].reset();
            $('#id-syarat').val("");
            $('#wajib').val("").trigger('change');
            $('#aktif').val("").trigger('change');
        };

        function fillform(vdt){
            let dt=vdt[0];
            $('#id-syarat').val(dt.id);
            $('#nama').val(dt.nama);
            $('#keterangan').val(dt.keterangan);
            $('#wajib').val(dt.wajib).trigger('change');
            $('#aktif').val(dt.aktif).trigger('change');
        }

        function tambah(){
            pilihBeasiswa();

            resetForm();
            var myModal1 = new bootstrap.Modal(document.getElementById('modal-syarat'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal1.toggle();
            //loadModal();
        }

        $("#fbeasiswa").submit(function(e) {
            e.preventDefault();
            tinymce.triggerSave();
            let formVal = $(this).serializeArray();
            formVal.push({name:"_token",value:$("meta[name='csrf-token']").attr("content")});
            formVal.push({name:"beasiswa_id",value:$("#beasiswa_id").val()});
            if($(this).validationEngine('validate')){
                appAjax('{{ route("syarat-save") }}', $.param(formVal)).done(function(vRet) {
                    //resetForm();
                    refresh();
                    if(vRet.status){
                        $('#id-syarat').val(vRet.id);
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetForm();
            var formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                cari:{
                    0:{srchVal:$(this).data("id")},
                },
            };
            appAjax("{{ route('syarat-search') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    var myModal = new bootstrap.Modal(document.getElementById('modal-syarat'), {
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
                appAjax("{{ route('syarat-delete') }}", formVal).done(function(vRet) {
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
