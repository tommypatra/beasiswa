<!--
=========================================================
* Material Dashboard 2 - v3.0.4
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="material/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="material/assets/img/favicon.png">
  <title>
    Dashboard {{ config('app.appname') }} v.{{ config('app.appversion') }}
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="material/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="material/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="material/assets/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />

  <link href="plugins/toaster/css/bootstrap-toaster.min.css" rel="stylesheet">
  <link href="plugins/select2/dist/css/select2.min.css" rel="stylesheet"> 
  <link href="plugins/select2/dist/css/select2.custom.css" rel="stylesheet"> 
  <link href="plugins/loading/loading.css" rel="stylesheet">   
  <style>
    .sidenav {
        z-index: 500;
    }    
  </style>    

  @yield('head')

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="js/define.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <div class="mloading"></div>

  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="material/assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">{{ config('app.appname') }} v.{{ config('app.appversion') }}</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">


    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
		  <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link text-white " href="{{ route('dashboard') }}">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
    
        @if(session()->get('akses') == 1)  
          @include('admin.partials.menu-admin')
        @endif
        @if(session()->get('akses') == 2)  
          @include('admin.partials.menu-pegawai')
        @endif
        @if(session()->get('akses') == 3)  
          @include('admin.partials.menu-mahasiswa')
        @endif

        @include('admin.partials.menu-general')
      </ul> 
	</div>
    <!-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn bg-gradient-primary mt-4 w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div> -->
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('admin.partials.navbar')
    <!-- End Navbar -->
	
    <div class="container-fluid py-4">
      <div class="row">
        @yield('container')
      </div>

      <footer class="footer py-4  ">
        @include('admin.partials.footer')
      </footer>

    </div>
  </main>
  {{-- @include('admin.partials.plugin') --}}

  <!-- MULAI MODAL GANTI AKSES -->
<div class="modal fade" id="modal-ganti-akses" role="dialog">
  <div class="modal-dialog">
      <form id="fGantiAkses">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Ganti Akses</h5>
                  <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
              </div>
              <div class="modal-body ">
                  <div class="row">
                      <div class="col-12">
                        <div id="akses-area"></div>
                      </div>
                  </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
              </div>
          </div>
      </form>
  </div>
</div>
<!-- AKHIR MODAL GANTI AKSES -->

  <!--   Core JS Files   -->
  <script src="js/jquery-3.6.3.min.js"></script>

  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <script src="material/assets/js/core/popper.min.js"></script>
  <script src="material/assets/js/core/bootstrap.min.js"></script>
  <script src="material/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="material/assets/js/plugins/smooth-scrollbar.min.js"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    $("#ganti-akses").click(function(){
      var myModal = new bootstrap.Modal(document.getElementById('modal-ganti-akses'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();

        let formVal={_token:$("meta[name='csrf-token']").attr("content")};
        appAjax('{{ route("user-label-akses") }}', formVal).done(function(vRet) {
          $("#akses-area").html(vRet.html);
        });
        
    });
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="material/assets/js/material-dashboard.min.js?v=3.0.4"></script>
  <script src="plugins/loading/loading.js"></script>
  <script src="plugins/toaster/js/bootstrap-toaster.min.js"></script>
  <script src="plugins/select2/dist/js/select2.min.js"></script>
  <script src="js/myapp.js"></script>
  <script src="js/select2lib.js"></script>

  @yield('scriptJs')
</body>
</html>