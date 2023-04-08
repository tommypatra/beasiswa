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
                    <h6 class="text-white text-capitalize ps-3">Daftar Beasiswa</h6>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-12 mb-3">

                        <div class="row input-group input-group-outline">
                            <div class="col-sm-4"> Tahun
                                <select class="form-control mb-3" name="tahun" id="tahun">                                
                                </select>
                            </div>
                            <div class="col-sm-8"> Beasiswa
                                <input type="text" class="form-control mb-3" name="namabeasiswa" id="namabeasiswa">                                
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
                    <div class="col-12" id="daftar-beasiswa">

                    </div>  
                </div>                
            </div>
        </div>
    </div>
</div>

<!-- MULAI MODAL -->
<div class="modal fade modal-lg" id="modal-syarat" role="dialog">
    <div class="modal-dialog">
        <input type="hidden" id="beasiswa_id">
        <input type="hidden" id="pendaftar_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">UPLOAD DOKUMEN SYARAT</h5>
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body ">
                <div class="row" id="html-syarat">
                
                </div>  
            </div>  
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
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
        sel2_datalokal("#tahun");

        $('.datepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
        });

        init();
        function init() {
            //untuk tahun beasiswa
            let formVal={_token:$("meta[name='csrf-token']").attr("content")};
            appAjax('{{ route("pendaftaranbeasiswa-init") }}', formVal).done(function(vRet) {
                refreshbeasiswa(vRet.data.beasiswa);
            });

            appAjax('{{ route("syarat-init") }}', formVal).done(function(vRet) {
                let vElement="#tahun";
                if(vRet.status){
                    let data=vRet.data.beasiswa;
                    $(vElement).empty();
                    if(data.length>0){
                        $(vElement).append($('<option>', {value:"", text: "- pilih -"}));
                        $.each(data, function( key, dp ) {
                            $(vElement).append($('<option>', {value:dp.tahun, text: dp.tahun}));
                        });
                        $(vElement).val(["{{ date('Y') }}"]).trigger('change');
                    }                    
                }
            });


        };

        function labelsyarat(data){
            let vhtml='<span class="mb-2 text-xs"><b>Dokumen yang disiapkan :</b>';
            vhtml+='<ul>';
            $.each(data, function( key, dp ) {
                vhtml+='<li>'+dp.nama+'</li>';
            });
            vhtml+='</ul></span>';
            return vhtml;
        }

        function labelujian(data){
            let vhtml='<span class="mb-2 text-xs"><b>Tahapan Ujian :</b>';
            vhtml+='<ul>';
            $.each(data, function( key, dp ) {
                vhtml+='<li>'+dp.ujian+'</li>';
            });
            vhtml+='</ul></span>';
            return vhtml;            
        }

        function labelpendaftaran(data,beasiswa_id){
            let vhtml='';
            if(data.length<1){
                vhtml='<a href="javascript:;" id="mendaftar-beasiswa" data-id="'+beasiswa_id+'" class="btn btn-sm bg-gradient-primary btn-sm mt-4 mb-0">Daftar Sekarang</a>';
            }else{
                vhtml='<div>SUDAH TERDAFTAR <a href="javascript:;" id="hapus-pendaftaran" data-id="'+data[0].id+'"><span class="material-icons">delete_forever</span></a></div>';
                vhtml+='<a href="javascript:;" id="upload-dokumen-beasiswa" data-beasiswa_id="'+beasiswa_id+'" data-id="'+data[0].id+'" class="btn btn-sm bg-gradient-success btn-sm mt-4 mb-0"><span class="material-icons">upload</span> Upload Dokumen</a>';
            }
            return vhtml;
        }

        function refreshbeasiswa(data){
            let vElement='#daftar-beasiswa';
            let vhtml='<div class="alert alert-danger alert-dismissible text-white" role="alert">'+
                            '<span class="text-sm">Tidak Ditemukan</span>'+
                                '<button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">'+
                                '<span aria-hidden="true">×</span>'+
                            '</button>'+
                        '</div>';
            if(data.length>0){
                vhtml='<ul class="list-group">';
                $.each(data, function( key, dp ) {
                    vhtml+='<li class="list-group-item border-0 d-flex p-4 mb-3 bg-gray-100 border-radius-lg">'+
                                '<div class="d-flex flex-column">'+
                                    '<h5 class="mb-3">'+dp.nama+'</h5>'+
                                    '<span class="mb-2 text-xs">Jadwal Pendaftaran : '+dp.daftar_mulai+' sd '+dp.daftar_selesai+'</span>'+
                                    '<span class="mb-2 text-xs">Jadwal Verifikasi : '+dp.verifikasi_mulai+' sd '+dp.verifikasi_selesai+'</span>'+
                                    labelsyarat(dp.syarat)+' '+labelujian(dp.ujian)+
                                '</div>'+
                                '<div class="ms-auto text-end">'+
                                    labelpendaftaran(dp.pendaftar,dp.id)+
                                '</div>'+
                            '</li>';
                });
                vhtml+='</ul>';
            }
            $(vElement).html(vhtml);
        }

        $("#tahun").change(function(){
            cariBeasiswa();
        })

        $("#namabeasiswa").change(function(){
            cariBeasiswa();
        })
        
        function cariBeasiswa(){
            let vTahun=$("#tahun").val();
            let vNama=$("#namabeasiswa").val();
            var formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                cari:{
                    0:{srchFld:'tahun',srchGrp:'where',srchVal:vTahun},
                    1:{srchFld:'nama',srchGrp:'like',srchVal:vNama},
                }
            };
            appAjax("{{ route('beasiswa-search') }}", formVal).done(function(vRet) {
                refreshbeasiswa(vRet.data);
            });
        }

        //untuk mendaftar beasiswa
        $(document).on("click","#mendaftar-beasiswa",function(){
            let formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                beasiswa_id:$(this).data("id"),
            };
            if(confirm("apakah anda yakin?"))
                appAjax('{{ route("pendaftaranbeasiswa-save") }}', formVal).done(function(vRet) {
                    if(vRet.status)
                        cariBeasiswa();
                    showmymessage(vRet.messages,vRet.status);
                });
        });

        //untuk mendaftar beasiswa
        $(document).on("click","#hapus-pendaftaran",function(){
            let formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                id:$(this).data("id"),
            };
            if(confirm("apakah anda yakin?"))
                appAjax('{{ route("pendaftaranbeasiswa-delete") }}', formVal).done(function(vRet) {
                    if(vRet.status)
                        cariBeasiswa();
                    showmymessage(vRet.messages,vRet.status);
                });
        });
                
        //untuk upload syarat
        $(document).on("click","#upload-dokumen-beasiswa",function(){
            $("#beasiswa_id").val($(this).data("beasiswa_id"));
            $("#pendaftar_id").val($(this).data("id"));
            uploadSyarat();    
        });

        function uploadSyarat(){
            vbeasiswa_id=$("#beasiswa_id").val();
            vpendaftar_id=$("#pendaftar_id").val();
            if(!$('#modal-syarat').hasClass('show')){
                var myModal1 = new bootstrap.Modal(document.getElementById('modal-syarat'), {
                    backdrop: 'static',
                    keyboard: false,
                });
                myModal1.toggle();
            }

            $('#html-syarat').html('');
            //loaddata
            let formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                beasiswa_id:vbeasiswa_id,
                pendaftar_id:vpendaftar_id,
            };
            appAjax('{{ route("pendaftaranbeasiswa-formupload") }}', formVal).done(function(vRet) {
                $('#html-syarat').html(vRet.html);
            });
        }

        function tutupModal() {
            const myModal = document.querySelector('#modal-syarat');
            const modal = bootstrap.Modal.getInstance(myModal);    
            modal.hide();
        }

        $(document).on("change",".mengupload-syarat",function(){
            let beasiswa_id = $("#beasiswa_id").val();
            let pendaftar_id = $("#pendaftar_id").val();
            let syarat_id = $(this).data("syarat_id");
            if(confirm("Upload sekarang?")){
                let formVal = new FormData();
                formVal.append("fileupload", $(this)[0].files[0]); 
                formVal.append("beasiswa_id", beasiswa_id); 
                formVal.append("pendaftar_id", pendaftar_id); 
                formVal.append("syarat_id", syarat_id); 
                formVal.append("_token", $("meta[name='csrf-token']").attr("content")); 
                appAjaxUpload('{{ route("pendaftaranbeasiswa-upload") }}', formVal).done(function(vRet) {
                    showmymessage(vRet.messages,vRet.status);
                    uploadSyarat();
                });
            }
            $(this).val(""); 
        });

        $(document).on("click",".btn-hapus-upload",function(){
            let formVal={_token:$("meta[name='csrf-token']").attr("content"),id:$(this).data("id")};
            if(confirm("apakah anda yakin?")){
                appAjax('{{ route("pendaftaranbeasiswa-upload-delete") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        uploadSyarat();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
            }
        })
    </script>
@endsection
