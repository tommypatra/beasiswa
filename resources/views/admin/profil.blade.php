@extends('admin.web')

@section('head')
    <link rel="stylesheet" type="text/css" href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="plugins/select2/dist/css/select2.custom.css"/>
    <link href="plugins/validationengine/css/validationEngine.jquery.css" rel="stylesheet">
@endsection

@section('container')
<div class="container-fluid px-2 px-md-4 mt-n4">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
      <span class="mask  bg-gradient-primary  opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4 mb-2">
        <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
                <?php
                    $foto=auth()->user()->foto;
                    if(!file_exists(auth()->user()->foto))
                        $foto="images/user-avatar.png";
                ?>
                <img src="{{ $foto }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm profil-foto">
            </div>
        </div>
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1 profil-nama" >
                {{ auth()->user()->nama }}
            </h5>
            <p class="mb-0 font-weight-normal text-sm profil-email" >
                {{ auth()->user()->email }}
            </p>
          </div>
        </div>
        
      </div>

        <div class="row">          
          <div class="col-12">
            <div class="card card-plain h-100">
              <div class="card-header pb-0 p-3">
                <div class="row">
                  <div class="col-md-8 d-flex align-items-center">
                    <h6 class="mb-0">Tentang Saya</h6>
                  </div>
                    <div class="col-md-4 text-end">
                        <a href="javascript:;">
                            <i class="fas fa-user-edit text-secondary text-sm" id="ganti-profil" data-bs-toggle="tooltip" data-bs-placement="top" title="ganti profil"></i>
                        </a>
                        <a href="javascript:;">
                            <i class="fas fa-key text-secondary text-sm" id="ganti-password" data-bs-toggle="tooltip" data-bs-placement="top" title="ganti password"></i>
                        </a>
                    </div>
                </div>
              </div>
              <div class="card-body p-3">
                <p class="text-sm" id="profil-tentang"></p>
                <hr class="horizontal gray-light my-4">
                <ul class="list-group">
                  <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Nama Lengkap:</strong> &nbsp; <span id="profil-nama-lengkap"></span></li>
                  <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Tempat/ Tanggal Lahir:</strong> &nbsp; <span id="profil-tempat-tanggal-lahir"></span></li>
                  <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">No. HP:</strong> &nbsp; <span id="profil-no-hp"></span></li>
                  <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Alamat:</strong> &nbsp; <span id="profil-alamat"></span></li>
                  <li class="list-group-item border-0 ps-0 pb-0">
                    <strong class="text-dark text-sm">Media Sosial:</strong> &nbsp;
                    <a class="btn btn-facebook btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;" id="profil-fb">
                      <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;" id="profil-twitter">
                      <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a class="btn btn-instagram btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;" id="profil-ig">
                      <i class="fab fa-instagram fa-lg"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>          
        </div>

    </div>
  </div>

<!-- MULAI MODAL -->
<div class="modal fade modal-lg" id="modal-profil" role="dialog">
    <div class="modal-dialog">
        <form id="fprofil" class="form-row">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profil Akun</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-12">
                            <div class="row gx-4 mb-2">
                                <div class="col-auto">
                                    <div class="avatar avatar-xl position-relative">
                                        <img src="images/user-avatar.png" alt="profile_image" id="upload-foto" class="w-100 border-radius-lg shadow-sm profil-foto">
                                        <input type="file" id="fileupload" name="fileupload" style="display:none" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-auto my-auto">
                                    <div class="h-100">
                                        <label class="col-form-label">Glr Depan/ Nama/ Glr Belakang</label>
                                        <div class="row input-group input-group-outline">
                                            <div class="col-sm-3 mb-3">
                                                <input type="text" class="form-control" name="glrdepan" id="glrdepan" placeholder="glr depan">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <input type="text" class="form-control validate[required]" name="nama" id="nama" placeholder="nama lengkap">
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <input type="text" class="form-control" name="glrbelakang" id="glrbelakang" placeholder="glr belakang">
                                            </div>
                                        </div>
                                        <p class="mb-0 font-weight-normal text-sm">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>                            
                            </div>
                        </div>

                        <div class="row input-group input-group-outline">
                            <div class="col-sm-12">
                                <label class="col-form-label">Tentang Saya</label>
                                <textarea class="form-control validate[required]" name="tentang" id="tentang" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row input-group input-group-outline">
                            <label class="col-form-label">Tempat/ Tanggal Lahir</label>
                            <div class="col-sm-6 mb-3">
                                <input type="text" class="form-control validate[required]" name="tempatlahir" id="tempatlahir" placeholder="tmp lahir">
                            </div>
                            <div class="col-sm-4 mb-3">
                                <input type="text" class="form-control datepicker validate[required]" name="tanggallahir" id="tanggallahir" placeholder="tgl lahir">
                            </div>
                        </div>


                        <div class="row input-group input-group-outline">
                            <div class="col-auto">
                                <div class="col-12">
                                    <label class="col-form-label">No. HP</label>
                                    <input type="text" class="form-control validate[required]" name="nohp" id="nohp" placeholder="no.hp">
                                </div>
                            </div>
                        </div>

                        <div class="row input-group input-group-outline">
                            <div class="col-sm-12">
                                <label class="col-form-label">Alamat</label>
                                <textarea class="form-control validate[required]" name="alamat" id="alamat" rows="4"></textarea>
                            </div>
                        </div>

                        <h6 class="mt-1 mb-1">Media Sosial</h6>
                        <div class="row input-group input-group-outline">
                            <div class="col-sm-4 mb-3">
                                <label class="col-form-label">FB</label>
                                <input type="text" class="form-control" name="fb" id="fb" placeholder="facebook">
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label class="col-form-label">IG</label>
                                <input type="text" class="form-control" name="ig" id="ig" placeholder="instagram">
                            </div>
                            <div class="col-sm-4 mb-3">
                                <label class="col-form-label">Twitter</label>
                                <input type="text" class="form-control" name="twitter" id="twitter" placeholder="twitter">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

<!-- MULAI MODAL PASS-->
<div class="modal fade" id="modal-password" role="dialog">
    <div class="modal-dialog">
        <form id="fpassword" class="form-row">
            <input type="hidden" name="status" id="status-password">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Password Akun</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="row input-group input-group-outline" id="pass-lama" style="display:none">
                            <label class="col-form-label">Password Lama</label>
                            <div class="col-sm-6 mb-3">
                                <input type="password" class="form-control validate[required]" name="passlama" placeholder="password lama">
                            </div>
                        </div>


                        <div class="row input-group input-group-outline" id="pass-baru">
                            <label class="col-form-label">Password Baru</label>
                            <div class="col-sm-6 mb-3">
                                <input type="password" class="form-control validate[required]" name="password" placeholder="password baru">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
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
<script src="js/select2lib.js" type="text/javascript"></script>
<script type="text/javascript">

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
    });

    init();
    function init() {
        let formVal={_token:$("meta[name='csrf-token']").attr("content")};
        appAjax('{{ route("user-init") }}', formVal).done(function(vRet) {
            loadProfil(vRet);
        });
    };    

    function setLink(elmnt,link){
        if(link==="")                
            link='javascipt:;';            
        $(elmnt).attr("href",link);
    }        

    function loadProfil(vdata){
        $('#fprofil')[0].reset();

        let time = (new Date()).getTime();
        let fotoDef = 'images/user-avatar.png';
        let data = replaceNull(vdata);

        $("#id").val(data.id);
        if(data.foto_user.length>0){
            srcFoto=data.foto_user[(data.foto_user.length)-1].file.path;
            fotoDef="{{ asset('storage') }}"+"/"+srcFoto;
        }
        $(".profil-foto").attr("src",fotoDef+"?v="+time);
        $(".profil-nama").html(data.nama);
        $(".profil-email").html(data.email);

        $("#profil-nama-lengkap").html(data.glrdepan+' '+data.nama+' '+data.glrbelakang);
        $("#profil-tempat-tanggal-lahir").html(data.tempatlahir+' '+data.tanggallahir);
        $("#profil-no-hp").html(data.nohp);
        $("#profil-tentang").html(data.tentang);
        $("#profil-alamat").html(data.alamat);
        
        $("#nama").val(data.nama);
        $("#glrdepan").val(data.glrdepan);
        $("#glrbelakang").val(data.glrbelakang);
        $("#tempatlahir").val(data.tempatlahir);
        $("#tanggallahir").val(data.tanggallahir);
        $("#nohp").val(data.nohp);
        $("#tentang").val(data.tentang);
        $("#alamat").val(data.alamat);
        $("#fb").val(data.fb);
        $("#ig").val(data.ig);
        $("#twitter").val(data.twitter);

        setLink("#profil-fb",data.fb);
        setLink("#profil-ig",data.ig);
        setLink("#profil-twitter",data.twitter);
    } 

    $("#upload-foto").click(function(){
        $('#fileupload').click();
    });

    $("#ganti-profil").click(function(){
        var myModal1 = new bootstrap.Modal(document.getElementById('modal-profil'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal1.toggle();
        //loadModal();
    });

    function resetFormPassword(){
        $('#fpassword')[0].reset();
        let formVal={_token:$("meta[name='csrf-token']").attr("content")};
        appAjax('{{ route("user-cekpassword") }}', formVal).done(function(vRet) {
            if(vRet.status){
                $("#pass-lama").show();
                $("#status-password").val("1");
            }else{
                $("#pass-lama").hide();
                $("#status-password").val("0");
            }
        });
    }

    $("#ganti-password").click(function(){
        resetFormPassword();
        var myModal2 = new bootstrap.Modal(document.getElementById('modal-password'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal2.toggle();


    });
    
    $("#fprofil").submit(function(e) {
        e.preventDefault();
        var form = $(this)[0];
        let formVal = new FormData(form);
        //formVal.append("ktm", $("#ktm")[0].files[0]); 
        formVal.append("_token", $("meta[name='csrf-token']").attr("content")); 
        if($(this).validationEngine('validate')){
            appAjaxUpload('{{ route("user-update") }}', formVal).done(function(vRet) {
            if(vRet.status){
                init();
            }
            showmymessage(vRet.messages,vRet.status);
        });
        }
    });

    $("#fpassword").submit(function(e) {
        e.preventDefault();
        let formVal = $(this).serializeArray();
        formVal.push({name:"_token",value:$("meta[name='csrf-token']").attr("content")});
        if($(this).validationEngine('validate')){
            appAjax('{{ route("user-update-password") }}', $.param(formVal)).done(function(vRet) {
                resetFormPassword();
                showmymessage(vRet.messages,vRet.status);
            });
        }
    });
</script>
@endsection
