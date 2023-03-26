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
    Masuk Sistem {{ config('app.appname') }}
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="material/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="material/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="material/assets/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />
  <link href="plugins/validationengine/css/validationEngine.jquery.css" rel="stylesheet">
  <link href="plugins/loading/loading.css" rel="stylesheet">   
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-200">
  <div class="mloading"></div>
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid ps-2 pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{ route('utama') }}">
				{{ config('app.appname') }} v.{{ config('app.appversion') }}
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <div class="navbar-nav mx-auto">
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ route('utama') }}">
                    <i class="fa fa-home opacity-6 text-dark me-1"></i>
                    Halaman Utama
                  </a>
                </li>
              </div>
              <ul class="navbar-nav d-lg-flex d-none">
                {{-- <li class="nav-item d-flex align-items-center">
                  <a class="btn btn-outline-primary btn-sm mb-0 me-2" href="{{ route('utama') }}">Halaman Utama</a>
                </li> --}}
                {{-- <li class="nav-item d-flex align-items-center">
                  <a class="btn btn-outline-primary btn-sm mb-0 me-2" href="{{ route('mendaftar') }}">Mendaftar</a>
                </li> --}}
                <li class="nav-item">
                  <a href="{{ route('login') }}" class="btn btn-sm mb-0 me-1 bg-gradient-dark">Masuk</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
      
    </div>
  </div>



  <main class="main-content  mt-0">

    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">

          <div class="toast fade hide p-2 bg-white" style="position: absolute;z-index:10000;" role="alert" id="loginAlert" data-bs-delay="2000" data-bs-autohide="true" aria-live="assertive" aria-atomic="true">
            <div class="toast-header border-0">
              <span class="me-auto font-weight-bold">Pesan</span>
              <i class="fas fa-times text-md ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
            </div>
            <hr class="horizontal dark m-0">
            <div class="toast-body" >
              {{ session('pesan') }}
            </div>
          </div>
    
  
          <div class="col-lg-4 col-md-8 col-12 mx-auto">

            <div class="toast fade hide p-2 bg-white" role="alert" id="loginAlert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header border-0">
                <span class="me-auto font-weight-bold">Pesan</span>
                <i class="fas fa-times text-md ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
              </div>
              <hr class="horizontal dark m-0">
              <div class="toast-body">
                {{ session('pesan') }}
              </div>
            </div>
  
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Masuk</h4>
                  <div class="row mt-3">
                    {{-- <div class="col-2 text-center ms-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center px-1">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-github text-white text-lg"></i>
                      </a>
                    </div> --}}
                    <div class="col-12 text-center me-auto">
                      <a class="btn btn-link px-3" href="{{ route('auth') }}">
                        <i class="fa fa-google text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">

            
                <form id="flogin" class="text-start">
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control validate[required,custom[email]]" name="email">
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control validate[required]" name="password">
                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Ingat saya</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Masuk</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    belum punya akun?
                    <a href="{{ route('auth') }}" class="text-primary text-gradient font-weight-bold">Mendaftar</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          @include("admin.partials.footer")
        </div>  
        {{-- 
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart" aria-hidden="true"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold text-white" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-12 col-md-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-white" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-white" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-white" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-white" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div> --}}
      </footer>
    </div>
  </main>



  <!--   Core JS Files   -->
  <script src="js/jquery-3.6.3.min.js"></script>

  <script src="material/assets/js/core/popper.min.js"></script>
  <script src="material/assets/js/core/bootstrap.min.js"></script>
  <script src="material/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="material/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src='plugins/validationengine/js/jquery.validationEngine.js'></script>
  <script src='plugins/validationengine/js/languages/jquery.validationEngine-id.js'></script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="material/assets/js/material-dashboard.min.js?v=3.0.4"></script>

  <script src="js/define.js"></script>
  <script src="plugins/loading/loading.js"></script>
  <script src="plugins/toaster/js/bootstrap-toaster.min.js"></script>
  <script src="plugins/select2/dist/js/select2.min.js"></script>
  <script src="js/myapp.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    
    var myToastEl = document.getElementById('loginAlert');
    var bsAlert = new bootstrap.Toast(myToastEl);    

    @if(session()->has('pesan'))
      bsAlert.show();
    @endif      

    $("#flogin").submit(function(e) {
      e.preventDefault();
      let formVal = $(this).serializeArray();
      formVal.push({name:"_token",value:$("meta[name='csrf-token']").attr("content")});
      if($(this).validationEngine('validate')){
          appAjax('{{ route("ceklogin") }}', $.param(formVal)).done(function(vRet) {
            showmymessage(vRet.messages,vRet.status);
            if(vRet.status){
              $('#flogin *').prop('disabled', true);
              window.setTimeout(function() {
                window.location.href = '{{ route("dashboard") }}';
              }, 1500);
            }else{
              $('#password').val("");
              $('#email').focus();
            }
          });
      }
    });

  </script>

</body>

</html>