@extends('layoutbootstrap')

@section('konten')

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="ms-auto me-3">
            <a href="{{url('logout')}}" class="btn btn-outline-primary">Logout</a>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Data COA</h5>

                <!-- Display Error jika ada error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Akhir Display Error -->

                <!-- Awal Dari Input Form -->
                <form action="{{ route('coa.store') }}" method="post">
                    @csrf
                    <div class="mb-3"><label for="kodeakunlabel">Kode Akun</label>
                    <input class="form-control form-control-solid" id="kode_akun" name="kode_akun" type="text" placeholder="Contoh: 1110" value="{{old('kode_akun')}}" ></div>

                    <div class="mb-3"><label for="namaakunlabel">Nama Akun</label>
                    <input class="form-control form-control-solid" id="nama_akun" name="nama_akun" type="text" placeholder="Contoh: Kas" value="{{old('nama_akun')}}">
                    </div>
        
                    <div class="mb-3"><label for="headerakunlabel">Header Akun</label>
                    <input class="form-control form-control-solid" id="header_akun" name="header_akun" type="text" placeholder="Contoh: 1" value="{{old('header_akun')}}">
                    </div>

                    <div class="mb-3">
                        <label for="tipe">Tipe Aktivitas</label>
                        <select name="tipe" class="form-select">
                            <option value="" disabled selected>Pilih Tipe</option>
                            <option value="Arus kas dari aktivitas operasional" {{ old('tipe') == 'Arus kas dari aktivitas operasional' ? 'selected' : '' }}>
                                Arus kas dari aktivitas operasional
                            </option>
                            <option value="Arus kas dari aktivitas investasi" {{ old('tipe') == 'Arus kas dari aktivitas investasi' ? 'selected' : '' }}>
                                Arus kas dari aktivitas investasi
                            </option>
                            <option value="Arus kas dari aktivitas pendanaan" {{ old('tipe') == 'Arus kas dari aktivitas pendanaan' ? 'selected' : '' }}>
                                Arus kas dari aktivitas pendanaan
                            </option>
                        </select>
                    </div>
                    <br>
                    <!-- untuk tombol simpan -->
                    
                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/coa') }}" role="button">Batal</a>
                    
                </form>
                <!-- Akhir Dari Input Form -->
            
          </div>
        </div>
      </div>
		
		
		
        
@endsection