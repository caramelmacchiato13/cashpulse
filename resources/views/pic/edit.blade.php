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
            <h5 class="card-title fw-semibold mb-4">Data PIC</h5>

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
                <form action="{{ route('pic.update', $pic->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <fieldset disabled>
                        <div class="mb-3"><label for="idpiclabel">Kode PIC</label>
                        <input class="form-control form-control-solid" id="kode_pic_tampil" name="kode_pic_tampil" type="text" placeholder="Contoh: PIC-001" value="{{$pic->kode_pic}}" readonly></div>
                    </fieldset>
                    <input type="hidden" id="kode_pic" name="kode_pic" value="{{$pic->kode_pic}}">

                    <div class="mb-3"><label for="namapiclabel">Nama PIC</label>
                    <input class="form-control form-control-solid" id="nama_pic" name="nama_pic" type="text" placeholder="Contoh: Grande" value="{{$pic->nama_pic}}">
                    </div>
                    
                    <div class="mb-3"><label for="alamatpiclabel">Email</label>
                    <input class="form-control form-control-solid" id="email" name="email" type="text" placeholder="Contoh: Grande" value="{{$pic->email}}">
                    </div>

                    <div class="mb-3"><label for="notelp">Nomor Telepon</label>
                    <input class="form-control form-control-solid" id="no_telp" name="no_telp" tyoe="text" placeholder="8624252772" value="{{$pic->no_telp}}">
                    </div>
                    <br>
                    <!-- untuk tombol simpan -->
                    
                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Ubah">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pic') }}" role="button">Batal</a>
                    
                </form>
                <!-- Akhir Dari Input Form -->
            
          </div>
        </div>
      </div>
		
		
		
        
@endsection