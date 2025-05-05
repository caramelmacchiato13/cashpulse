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
            <!-- <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li> -->
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
            <h5 class="card-title fw-semibold mb-4">Data Mitra</h5>

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
                <form action="{{ route('mitra.update', $mitra->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <fieldset disabled>
                        <div class="mb-3"><label for="idmitralabel">Kode Mitra</label>
                        <input class="form-control form-control-solid" id="id_mitra_tampil" name="id_mitra_tampil" type="text" placeholder="Contoh: MTR-001" value="{{$mitra->id_mitra}}" readonly></div>
                    </fieldset>
                    <input type="hidden" id="id_mitra" name="id_mitra" value="{{$mitra->id_mitra}}">

                    <div class="mb-3"><label for="namamitralabel">Nama Mitra</label>
                    <input class="form-control form-control-solid" id="nama_mitra" name="nama_mitra" type="text" placeholder="Contoh: PT Sejahtera" value="{{$mitra->nama_mitra}}">
                    </div>
                    
        
                    <div class="mb-3"><label for="alamatmitralabel">Alamat</label>
                        <textarea class="form-control form-control-solid" id="alamat_mitra" name="alamat_mitra" rows="3" placeholder="Cth: Jl Pelajar Pejuan 45">{{$mitra->alamat_mitra}}</textarea>
                    </div>

                    <div class="mb-0"><label for="notelp">Nomor Telepon</label>
                    <input class="form-control form-control-solid" id="no_telp" name="no_telp" rows="3" placeholder="8624252772" value="{{$mitra->no_telp}}">
                    </div>
                    <br>
                    <!-- untuk tombol simpan -->
                    
                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Ubah">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/mitra') }}" role="button">Batal</a>
                    
                </form>
                <!-- Akhir Dari Input Form -->
            
          </div>
        </div>
      </div>
		
		
		
        
@endsection