@extends('layoutbootstrap')

@section('konten')
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a href="#" class="nav-link nav-icon-hover" data-bs-toggle="dropdown">
                  <!-- <i class="ti ti-bell-ringing"></i>
                  <div class="notification bg-primary rounded-circle"></div> -->
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('logout') }}" class="btn btn-outline-primary mx-2">Logout</a>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="card overflow-hidden">
              <div class="card-body">
                <h5 class="card-title">Total Kas Masuk</h5>
                <div class="d-flex align-items-center">
                  <h2 class="mb-0 d-flex align-items-baseline fs-5">
                    <span class="me-1">Rp</span> 
                    {{ number_format($totalKasMasuk, 0, ',', '.') }}
                  </h2>
                  <span class="text-success ms-2">
                    <i class="ti ti-arrow-up"></i> {{ $persenKasMasuk }}%
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card overflow-hidden">
              <div class="card-body">
                <h5 class="card-title">Total Kas Keluar</h5>
                <div class="d-flex align-items-center">
                  <h2 class="mb-0 d-flex align-items-baseline fs-5">
                    <span class="me-1">Rp</span> 
                    {{ number_format($totalKasKeluar, 0, ',', '.') }}
                  </h2>
                  <span class="text-danger ms-2">
                    <i class="ti ti-arrow-down"></i> {{ $persenKasKeluar }}%
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card overflow-hidden">
              <div class="card-body">
                <h5 class="card-title">Saldo Kas</h5>
                <div class="d-flex align-items-center">
                  <h2 class="mb-0 d-flex align-items-baseline fs-5">
                    <span class="me-1">Rp</span> 
                    {{ number_format($saldoKas, 0, ',', '.') }}
                  </h2>
                  <span class="text-primary ms-2">
                    <i class="ti ti-wallet"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="card overflow-hidden">
              <div class="card-body">
                <h5 class="card-title">Jumlah Proyek</h5>
                <div class="d-flex align-items-center">
                  <h2 class="mb-0 fs-6">{{ $jumlahProyek }}</h2>
                  <span class="text-info ms-2">
                    <i class="ti ti-briefcase"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-4">Proyek Terbaru</h5>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Nama Proyek</th>
                        <th>Total Kas</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($proyekTerbaru as $proyek)
                      <tr>
                        <td>{{ $proyek->nama_projek }}</td>
                        <td>Rp {{ number_format($proyek->total_kas ?? 0, 0, ',', '.') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-4">Transaksi Kas Masuk Terakhir</h5>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($transaksiMasukTerakhir as $transaksi)
                      <tr>
                        <td>{{ $transaksi->tanggal }}</td>
                        <td>{{ $transaksi->keterangan }}</td>
                        <td>Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title mb-4">Transaksi Kas Keluar Terakhir</h5>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($transaksiKeluarTerakhir as $transaksi)
                      <tr>
                        <td class="small">{{ $transaksi->tanggal }}</td>
                        <td>{{ $transaksi->keterangan }}</td>
                        <td>Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection