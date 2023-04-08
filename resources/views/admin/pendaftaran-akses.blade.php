@extends('admin.web')

@section('head')
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="plugins/validationengine/css/validationEngine.jquery.css" rel="stylesheet">
@endsection

@section('container')

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Pendaftaran Akses</h6>
                </div>
            </div>
            <div class="card-body pb-2">
                <div class="row">

                    <div class="col-12">
                        <div class="row gx-4 mb-2">
                            <div class="col-auto">
                                <div class="avatar avatar-xl position-relative">
                                    <img src="images/user-avatar.png" alt="profile_image" class="w-100 border-radius-lg shadow-sm profil-foto">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1 profil-nama" >
                                        {{ auth()->user()->nama }}
                                    </h5>
                                    <p class="mb-0 font-weight-normal text-sm profil-email">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>
                            </div>                            
                        </div>
                    </div>  

                </div>                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Admin</p>
            <h4 class="mb-0" id="jum-admin">0 Akun</h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
            <div id="status-admin"></div>
            <div type="button" class="btn btn-outline-primary btn-sm mb-0" id="admin-daftar"><span class="material-icons">web</span> Daftar</div>
        </div>
        </div>
    </div>

    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Pegawai</p>
            <h4 class="mb-0" id="jum-pegawai">0 Akun</h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
            <div id="status-pegawai"></div>
            <div type="button" class="btn btn-outline-primary btn-sm mb-0" id="pegawai-sinkron"><span class="material-icons">sync</span> Sinkronisasi SIMPEG</div>
        </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
        <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Mahasiswa</p>
            <h4 class="mb-0" id="jum-mahasiswa">0 Akun</h4>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        {{-- <div class="btn btn-primary btn-sm" id="mahasiswa-sinkron"><span class="material-icons">sync</span> Sinkron Mahasiswa</div> --}}
                            
        <div class="card-footer p-3">
            <div id="status-mahasiswa"></div>
            <div type="button" class="btn btn-outline-primary btn-sm mb-0" id="mahasiswa-sinkron"><span class="material-icons">sync</span> Sinkronisasi SIA</div>
        </div>
        </div>
    </div>    
</div>


<!-- MULAI MODAL ADMIN -->
<div class="modal fade" id="modal-admin" role="dialog">
    <div class="modal-dialog">
        <form id="fadmin">
            <input type="hidden" name="id" id="id-admin">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PENDAFTARAN AKSES ADMIN</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-12">
                            <div class="row gx-4 mb-2">
                                <div class="col-auto">
                                    <div class="avatar avatar-xl position-relative">
                                        <img src="images/user-avatar.png" alt="profile_image" class="w-100 border-radius-lg shadow-sm profil-foto">
                                    </div>
                                </div>
                                <div class="col-auto my-auto">
                                    <div class="h-100">
                                        <h5 class="mb-1 profil-nama">
                                            {{ auth()->user()->nama }}
                                        </h5>
                                        <p class="mb-0 font-weight-normal text-sm profil-email">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>                            
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="kel" class="form-label">Surat Tugas</label>
                            <input class="validate[required]" type="file" name="fileupload">
                            <img src="images/surat.png" alt="Surat Tugas" id="srt" width="100%" >
                            <div id="srt-hapus"></div>
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
<!-- AKHIR MODAL ADMIN -->

@endsection

@section("scriptJs")
    <script src='plugins/bootstrap-material-moment/moment.js'></script>
    <script src='plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'></script>
    <script src='plugins/validationengine/js/jquery.validationEngine.js'></script>
    <script src='plugins/validationengine/js/languages/jquery.validationEngine-id.js'></script>

    <script type="text/javascript">
        sel2_aktif2("#aktif");
        sel2_datalokal("#prodi_id");

        $('.mydatepicker').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
        });
        init();
        //resetAdmin();
        
        function init() {
            let time = (new Date()).getTime();
            let fotoDef = 'images/user-avatar.png';            
            let formVal={_token:$("meta[name='csrf-token']").attr("content")};
            appAjax('{{ route("pendaftaran-init") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    let jumlah=vRet.data.jumlahAkun.original;
                    let aksesAkun=vRet.data.akses;
                    let akun =vRet.data.akunLogin.original;
                    // console.log(akun); 
                    if(akun.foto_user.length>0){
                        srcFoto=akun.foto_user[(akun.foto_user.length)-1].file.path;
                        fotoDef="{{ asset('storage') }}"+"/"+srcFoto;
                    }
                    $(".profil-foto").attr("src",fotoDef+"?v="+time);
                    $(".profil-nama").html(akun.nama);
                    $(".profil-email").html(akun.email);

                    $('#jum-admin').html(jumlah.admin+" Akun");
                    $('#jum-pegawai').html(jumlah.pegawai+" Akun");
                    $('#jum-mahasiswa').html(jumlah.mahasiswa+" Akun");

                    setLabel("admin",aksesAkun.admin);
                    setLabel("pegawai",aksesAkun.pegawai);
                    setLabel("mahasiswa",aksesAkun.mahasiswa);

                    // console.log(prodi);
                    // $('#prodi_id').empty();
                    // if(prodi.length>0){
                    //     $.each(prodi, function( key, dp ) {
                    //         $("#prodi_id").append($('<option>', {value:dp.id, text: dp.prodi}));
                    //     });
                    // }
                    
                }
            });
        };

      

        function setLabel(velement,vdata){
            let vhtml='<p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Belum</span> Terdaftar</p>';
                                
            //console.log(velement+' '+vdata.id);
            if(vdata.length>0){
                vhtml='<p class="mb-0"><span class="text-success text-sm font-weight-bolder">Sudah</span> Terdaftar</p>';
                $.each(vdata, function( key, dp ) {
                    vhtml=vhtml+'<p class="mb-0"><span class="material-icons">keyboard_arrow_right</span> '+dp.noid+' ';
                    let vlblstatus='<span class="material-icons text-danger">clear</span> <span class="text-danger" style="font-size:10px">(hubungi admin)</span>';
                    if(dp.aktif=='1')
                        vlblstatus='<span class="material-icons text-success">done_all</span>';
                    vhtml=vhtml+vlblstatus+'</p>';
                });
            }
            $('#status-'+velement).html(vhtml);
        }


        function regMahasiswa(vData){
            let formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                data:vData,
            };
            appAjax('{{ route("pendaftaran-mahasiswa-create") }}', formVal).done(function(vRet) {
                if(vRet.status)
                    init();
                showmymessage(vRet.messages,vRet.status);
            });
        }

        $("#mahasiswa-sinkron").click(function() {
            if(confirm('apakah anda yakin?')){
                let formVal={email:"{{ auth()->user()->email }}"};
                appAjax('https://sia.iainkendari.ac.id/api/v2/daftarnim', formVal).done(function(vRet) {
                    if(vRet.status){
                        $.each(vRet.data, function( key, dp ) {
                            regMahasiswa(dp);
                        });
                    }else{
                        let pesan=vRet.pesan;
                        if(!$.isArray(pesan))
                            pesan=[vRet.pesan];
                        showmymessage(pesan,vRet.status);
                    }
                });
            }
        });
        
        function regPegawai(vData){
            let formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                data:vData,
            };
            appAjax('{{ route("pendaftaran-pegawai-create") }}', formVal).done(function(vRet) {
                if(vRet.status)
                    init();
                showmymessage(vRet.messages,vRet.status);
            });
        }

        $("#pegawai-sinkron").click(function() {
            if(confirm('apakah anda yakin?')){
                let formVal={email:"{{ auth()->user()->email }}"};
                appAjax('https://simpeg.iainkendari.ac.id/newapi/detailpegawai', formVal).done(function(vRet) {
                    if(vRet.status){
                        $.each(vRet.data, function( key, dp ) {
                            regPegawai(dp);
                        });
                    }else{
                        let pesan=vRet.pesan;
                        if(!$.isArray(pesan))
                            pesan=[vRet.pesan];
                        showmymessage(pesan,vRet.status);
                    }
                });
            }
        });

        // untuk admin

        function resetAdmin(){
            let time = (new Date()).getTime();
            $('#fadmin')[0].reset();
            $('#fadmin #id').val("");
            $("#srt-hapus").html('');
            $("#srt").attr("src","images/surat.png");
        }

        function loadAdmin() {
            let time = (new Date()).getTime();
            let formVal={_token:$("meta[name='csrf-token']").attr("content")};
            resetAdmin();
            appAjax('{{ route("admin-search") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    let akun=vRet.data[0];
                    let admin=akun.admin;
                    if(admin.length>0){
                        $("#id-admin").val(admin[0].id);
                        if(admin[0].file!==null){
                            $("#srt").attr("src","{{ asset('storage') }}"+"/"+admin[0].file.path+"?v="+time);
                            $("#srt-hapus").html('<div class="btn btn-danger btn-sm" data-id="'+admin[0].file.id+':'+admin[0].id+'" btn-sm" id="btn-srt-hapus">X</a>');
                        }
                    }
                    //let fileupload=vRet
                }
            });
        };

        $(document).on("click","#admin-daftar",function(){
            var myModal = new bootstrap.Modal(document.getElementById('modal-admin'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
            loadAdmin();
        });

        $("#fadmin").submit(function(e) {
            e.preventDefault();
            var form = $(this)[0];
            let formVal = new FormData(form);
            //formVal.append("ktm", $("#ktm")[0].files[0]); 
            formVal.append("_token", $("meta[name='csrf-token']").attr("content")); 
            if($(this).validationEngine('validate')){
                appAjaxUpload('{{ route("pendaftaran-admin-create") }}', formVal).done(function(vRet) {
                if(vRet.status){
                    loadAdmin();
                    init();
                }
                showmymessage(vRet.messages,vRet.status);
            });
            }
        });

        $(document).on("click","#btn-srt-hapus",function(){
            hapusUpload($(this).data("id"),"admin");
        });

        function hapusUpload(vid,vgrup){
            let formVal={
                _token:$("meta[name='csrf-token']").attr("content"),
                id:vid,
                grup:vgrup
            };
            if(confirm("apakah anda yakin?"))
                appAjax('{{ route("pendaftaran-delete-upload") }}', formVal).done(function(vRet) {
                    if(vRet.status){
                        loadAdmin();
                    }
                    showmymessage(vRet.messages,vRet.status);
                });
        }

    </script>
@endsection
