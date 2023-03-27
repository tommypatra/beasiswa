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
                    <h6 class="text-white text-capitalize ps-3">Publikasi Website</h6>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-12">

                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="cekSemua"></th>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Konten</th>
                                        <th>Aktif</th>
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
<div class="modal fade modal-lg" id="modal-publikasi" role="dialog">
    <div class="modal-dialog">
        <form id="fpublikasi">
            <input type="hidden" name="id" id="id-publikasi">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM PUBLIKASI WEB</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">

                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Judul</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control validate[required]" name="judul" id="judul" placeholder="judul">
                        </div>
                    </div>
                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Slug/ Tanggal</label>
                        <div class="col-sm-4 mb-3">
                            <input type="text" class="form-control datepicker validate[required]" name="tgl" id="tgl" placeholder="tanggal" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-sm-8 ">
                            <input type="text" class="form-control" name="slug" id="slug" placeholder="slug">
                        </div>
                    </div>
                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Kategori</label>
                        <div class="col-sm-4">
                            <select class="form-control validate[required]" name="kategori_id" id="kategori">                                
                            </select>
                        </div>
                    </div>

                    <div class="row input-group input-group-outline">
                        <div class="col-sm-12">
                            <label class="col-form-label">Konten</label>
                            <textarea class="validate[required]" name="konten" id="konten"></textarea>
                        </div>
                    </div>
                    
                    <div class="row input-group input-group-outline">
                        <label class="col-form-label">Aktif</label>
                        <div class="col-sm-4">
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
        sel2_datalokal("#kategori");

        $('.datepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
        });
        init();

        tinymce.init({
            selector:'#konten',
            mode : 'textareas',
            forced_root_block : false,            
        });

        $("#judul").change(function(){
            let slug=convertToSlug($(this).val(),$("#id-publikasi").val());
            if(slug)
                $("#slug").val(slug);
        });

        function init() {
            let formVal={_token:$("meta[name='csrf-token']").attr("content")};
            appAjax('{{ route("publikasi-init") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    let data=vRet.data.kategori;
                    $('#kategori').empty();
                    if(data.length>0){
                        $.each(data, function( key, dp ) {
                            $("#kategori").append($('<option>', {value:dp.id, text: dp.kategori}));
                        });
                    }                    
                }
            });
        };

        var dtTable = $('.datatable').DataTable({
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [25, 50, 75, -1],
                ["25", "50", "75", "Semua"]
            ],
            ajax: {
                url: "{{ route('publikasi-read') }}",
                dataType: "json",
                type: "POST",
                data: function (d) {
                    d._token = $("meta[name='csrf-token']").attr("content");  
                },
                dataSrc: function (json) {
                    return json.data;
                },
            },
            "order": [
                [2, "desc"],
                [6, "asc"],
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
                    text: 'Tambah',
                    action: function ( e, dt, node, config ) {
                        tambah();
                    }                
                },
                {
                    text: 'Refresh',
                    action: function ( e, dt, node, config ) {
                        refresh();
                    }                
                },
            ],
            columns: [
                {data: 'cek',className: "text-center", width:"5%", orderable: false, searchable: false},
                {data: 'no', width:"5%",searchable: false},
                {data: 'tgl', width:"10%",},
                {data: 'kontenWeb', width:"65%",orderable: false, searchable: false},
                {data: 'aktif', width:"5%",orderable: false, searchable: false},
                {data: 'action', width:"5%",className: "text-center", orderable: false, searchable: false},
                {data: 'judul', visible: false},
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
            $('#fpublikasi')[0].reset();
            $('#id').val("");
            $('#id-publikasi').val("");
            $('#kategori_id').val("").trigger('change');
            $('#aktif').val("").trigger('change');
            tinymce.get('konten').setContent('');
        };

        function fillform(vdt){
            let dt=vdt[0];
            $('#id-publikasi').val(dt.id);
            $('#judul').val(dt.judul);
            $('#slug').val(dt.slug);
            tinymce.get('konten').setContent(dt.konten);
            $('#tgl').val(dt.tgl);
            $('#kategori_id').val(dt.kategori_id).trigger('change');
            $('#aktif').val(dt.aktif).trigger('change');
        }

        function tambah(){
            resetForm();
            var myModal1 = new bootstrap.Modal(document.getElementById('modal-publikasi'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal1.toggle();
            //loadModal();
        }

        $("#fpublikasi").submit(function(e) {
            e.preventDefault();
            tinymce.triggerSave();
            let formVal = $(this).serializeArray();
            formVal.push({name:"_token",value:$("meta[name='csrf-token']").attr("content")});
            if($(this).validationEngine('validate')){
                appAjax('{{ route("publikasi-create") }}', $.param(formVal)).done(function(vRet) {
                    //resetForm();
                    refresh();
                    if(vRet.status){
                        $('#id-publikasi').val(vRet.id);
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        });

        //ganti
        $(document).on("click",".btn-ganti",function(){
            resetForm();
            var formVal={_token:$("meta[name='csrf-token']").attr("content"),srchFld:'id',srchGrp:'where',srchVal:$(this).data("id")};
            appAjax("{{ route('publikasi-search') }}", formVal).done(function(vRet) {
                if(vRet.status){
                    var myModal = new bootstrap.Modal(document.getElementById('modal-publikasi'), {
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
                appAjax("{{ route('publikasi-delete') }}", formVal).done(function(vRet) {
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

        $(document).on("change",'.fileupload',function() {
            let beritaid = $(this).data("beritaid");
            let form = $("#fupload"+beritaid)[0];
            let formVal = new FormData(form);
            formVal.append("_token",$("meta[name='csrf-token']").attr("content")); 
            formVal.append("berita_id", beritaid); 
            formVal.append("fileupload", $(this)[0].files[0]); 
            // if(confirm("apakah anda yakin?")){
            appAjaxUpload('{{ route("publikasi-upload") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    refresh();
                }
                showmymessage(vRet.messages,vRet.status);
            });
            $(this).val(null);
            // }
        });

        $(document).on("click",".btn-hapus-upload",function(){
            let formVal={_token:$("meta[name='csrf-token']").attr("content"),id:$(this).data("id")};
            if(confirm("apakah anda yakin?")){
                appAjax('{{ route("publikasi-upload-delete") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        refresh();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        })


    </script>
@endsection
