<li class="nav-item">
    <a data-bs-toggle="collapse" href="#menuKonten" class="nav-link text-white collapsed" aria-controls="menuKonten" role="button" aria-expanded="false">
        {{-- <i class="fab fa-laravel" aria-hidden="true"></i> --}}
        <i class="material-icons opacity-10">web</i>          
        <span class="nav-link-text ms-2 ps-1">Konten Web</span>
    </a>    
    <div class="collapse" id="menuKonten" style="">
        <ul class="nav ">
            <li class="nav-item ">
                <a class="nav-link text-white" href="{{ route('publikasi') }}">
                <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                <span class="sidenav-normal  ms-2  ps-1"> Publikasi</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link text-white" href="{{ route('upload') }}">
                <span class="sidenav-mini-icon"><i class="material-icons opacity-10">navigate_next</i></span>
                <span class="sidenav-normal  ms-2  ps-1"> File Upload</span>
                </a>
            </li>
        </ul>
    </div>    
</li>    