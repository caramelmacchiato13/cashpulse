

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
	
	
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="{{asset('img/logo_2.png')}}" width="250" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('dashboardbootstrap')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('berita1/galeri')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-calendar-time"></i>
                </span>
                <span class="hide-menu">Berita</span>
              </a>
            </li> -->
            @if(Session::get('kelompok')=='keuangan')
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Masterdata</span>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('coa')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-book"></i>
                </span>
                <span class="hide-menu">COA</span>
              </a>
            </li>
            @endif

            <!-- Tambahan pengecekan session kelompok apakah admin atau bukan -->
          @if(Session::get('kelompok')=='keuangan')
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('timeline')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-calendar-time"></i>
                </span>
                <span class="hide-menu">Timeline</span>
              </a>
            </li> -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('mitra')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-building"></i>
                </span>
                <span class="hide-menu">Mitra</span>
              </a>
            </li>
            @endif
            <!-- Akhir pengecekan admin kelompok -->

            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('jenispendapatan')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-businessplan"></i>
                </span>
                <span class="hide-menu">Jenis Pendapatan</span>
              </a>
            </li> -->
            
            <!-- Tambahan pengecekan session kelompok apakah admin atau bukan -->
          @if(Session::get('kelompok')=='keuangan')
          <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('pic')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">PIC</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('projek')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-folder"></i>
                </span>
                <span class="hide-menu">Proyek</span>
              </a>
            </li>
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('pic')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-presentation"></i>
                </span>
                <span class="hide-menu">PIC</span>
              </a>
            </li> -->

            @endif
            <!-- Akhir pengecekan admin kelompok -->

            @if(Session::get('kelompok')=='keuangan')
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Transaksi</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('kasmasuk')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-arrow-bar-right"></i>
                </span>
                <span class="hide-menu">Kas Masuk</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url ('KasKeluar')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-arrow-bar-left"></i>
                </span>
                <span class="hide-menu">Kas Keluar</span>
              </a>
            </li>
            @endif
            
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">LAPORAN</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ url('jurnal/umum') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-notebook"></i>
                </span>
                <span class="hide-menu">Jurnal Umum</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ url('bukubesar') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-file-invoice"></i>
                </span>
                <span class="hide-menu">Buku Besar</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ url('laporan/arus-kas') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-chart-line"></i>
                </span>
                <span class="hide-menu">Arus Kas</span>
              </a>
            </li>

          </ul>

          <div class="unlimited-access hide-menu position-relative mb-7 mt-5 rounded">
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->