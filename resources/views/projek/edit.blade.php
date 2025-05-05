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
            </li>   -->
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
            <h5 class="card-title fw-semibold mb-4">Data Proyek</h5>  
  
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
                <form action="{{ route('projek.update', $projek->id) }}" method="post">  
                    @csrf  
                    @method('PUT')  
                    <fieldset disabled>  
                        <div class="mb-3"><label for="kodeprojeklabel">Kode Proyek</label>  
                        <input class="form-control form-control-solid" id="kode_projek_tampil" name="kode_projek_tampil" type="text" placeholder="Contoh: PR-001" value="{{$projek->kode_projek}}" readonly></div>  
                    </fieldset>  
                    <input type="hidden" id="kode_projek" name="kode_projek" value="{{$projek->kode_projek}}">  
  
                    <div class="mb-3"><label for="namaprojeklabel">Nama Proyek</label>  
                    <input class="form-control form-control-solid" id="nama_projek" name="nama_projek" type="text" placeholder="Contoh: Toko Mukena Sejuk Menenangkan" value="{{$projek->nama_projek}}">  
                    </div>  
                      
                    <div class="mb-3"><label for="jenisprojeklabel">Tanggal Mulai</label>  
                    <input class="form-control form-control-solid" id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{$projek->tanggal_mulai}}">  
                    </div>  
  
                    <div class="mb-3"><label for="jenisprojeklabel">Tanggal Selesai</label>  
                    <input class="form-control form-control-solid" id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{$projek->tanggal_selesai}}">  
                    </div>  

                    <!-- Di create.blade.php dan edit.blade.php, tambahkan sebelum besar_anggaran -->
                    <div class="mb-3">
                        <label for="pic_id">PIC Proyek</label>
                        <select class="form-control form-control-solid" id="pic_id" name="pic_id">
                            <option value="">Pilih PIC</option>
                            @foreach($pics as $pic)
                                <option value="{{ $pic->id }}" {{ old('pic_id', $projek->pic_id ?? '') == $pic->id ? 'selected' : '' }}>
                                    {{ $pic->nama_pic }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mitra_id">Mitra Proyek</label>
                        <select class="form-control form-control-solid" id="mitra_id" name="mitra_id">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $mitra)
                                <option value="{{ $mitra->id }}" {{ old('mitra_id', $projek->mitra_id ?? '') == $mitra->id ? 'selected' : '' }}>
                                    {{ $mitra->nama_mitra }}
                                </option>
                            @endforeach
                        </select>
                    </div>
  
                    <!-- <div class="mb-3"><label for="namaprogramlabel">Besar Anggaran</label>  
                    <input class="form-control form-control-solid" id="besar_anggaran" name="besar_anggaran" type="text" placeholder="Contoh: Kedaireka" value="{{$projek->besar_anggaran}}">  
                    </div>   -->
                    <br>  
                    <!-- untuk tombol simpan -->  
                      
                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Ubah">  
  
                    <!-- untuk tombol batal simpan -->  
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/projek') }}" role="button">Batal</a>  
                      
                </form>  
                <!-- Akhir Dari Input Form -->  
              
          </div>  
        </div>  
      </div>  
@endsection  
