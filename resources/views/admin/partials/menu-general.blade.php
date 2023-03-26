    <li class="nav-item mt-3">
      <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('user-profil') }}">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">person</i>
        </div>
        <span class="nav-link-text ms-1">Profile</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('pendaftaran-akses') }}">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">verified_user</i>
        </div>
        <span class="nav-link-text ms-1">Pendaftaran Akses</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white" href="javascript:;" id="ganti-akses">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">low_priority</i>
        </div>
        <span class="nav-link-text ms-1">Ganti Akses</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-white " href="{{ route('logout') }}">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
          <i class="material-icons opacity-10">power_settings_new</i>
        </div>
        <span class="nav-link-text ms-1">Keluar</span>
      </a>
    </li>
