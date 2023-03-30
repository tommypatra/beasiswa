
    @include('admin.partials.menu-konten')

    <li class="nav-item">
      <a data-bs-toggle="collapse" href="#menuBeasiswa" class="nav-link text-white collapsed" aria-controls="menuBeasiswa" role="button" aria-expanded="false">
          <i class="material-icons opacity-10">view_list</i>      
          <span class="nav-link-text ms-2 ps-1">Seleksi</span>
      </a>    
      <div class="collapse" id="menuBeasiswa" style="">
          <ul class="nav ">
              <li class="nav-item ">
                  <a class="nav-link text-white" href="{{ route('beasiswa') }}">
                  <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                  <span class="sidenav-normal  ms-2  ps-1"> Jadwal</span>
                  </a>
              </li>
              <li class="nav-item ">
                  <a class="nav-link text-white" href="{{ route('syarat') }}">
                  <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                  <span class="sidenav-normal  ms-2  ps-1"> Syarat</span>
                  </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link text-white" href="{{ route('ujian') }}">
                <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                <span class="sidenav-normal  ms-2  ps-1"> Ujian</span>
                </a>
            </li>
          </ul>
      </div>    
    </li>    

    <li class="nav-item">
      <a data-bs-toggle="collapse" href="#menuPenerima" class="nav-link text-white collapsed" aria-controls="menuPenerima" role="button" aria-expanded="false">
          <i class="material-icons opacity-10">people</i>      
          <span class="nav-link-text ms-2 ps-1">Penerima</span>
      </a>    
      <div class="collapse" id="menuPenerima" style="">
          <ul class="nav ">
              <li class="nav-item ">
                  <a class="nav-link text-white" href="https://material-dashboard-pro-laravel.creative-tim.com/user-profile">
                  <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                  <span class="sidenav-normal  ms-2  ps-1"> SK Penerima</span>
                  </a>
              </li>
              <li class="nav-item ">
                  <a class="nav-link text-white" href="https://material-dashboard-pro-laravel.creative-tim.com/users-management">
                  <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                  <span class="sidenav-normal  ms-2  ps-1"> Daftar Penerima</span>
                  </a>
              </li>
          </ul>
      </div>    
    </li>    

    <li class="nav-item">
      <a class="nav-link text-white " href="material/pages/tables.html">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">web</i>
        </div>
        <span class="nav-link-text ms-1">Pendaftar</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="material/pages/billing.html">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">receipt_long</i>
        </div>
        <span class="nav-link-text ms-1">Penguji</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="material/pages/billing.html">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">receipt_long</i>
        </div>
        <span class="nav-link-text ms-1">Pembagian</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="material/pages/billing.html">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">receipt_long</i>
        </div>
        <span class="nav-link-text ms-1">Nilai</span>
      </a>
    </li>
