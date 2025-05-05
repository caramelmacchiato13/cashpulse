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
                <li class="nav-item">    
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)">    
                        <!-- <i class="ti ti-bell-ringing"></i>    
                        <div class="notification bg-primary rounded-circle"></div>     -->
                    </a>    
                </li>    
            </ul>    
            <div class="ms-auto me-3">
            <a href="{{url('logout')}}" class="btn btn-outline-primary">Logout</a>
          </div>
        </nav>    
    </header>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Tambah Kas Keluar</h5>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('KasKeluar.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="no_kaskeluar" class="form-label">No Kas Keluar</label>
                        <input type="text" class="form-control" name="no_kaskeluar" value="{{ $noKasKeluar }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Pembelian Spare Part" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipe" class="form-label">Tipe</label>
                        <select name="tipe" class="form-select" required>
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
                    
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Nominal</label>
                        <input type="number" class="form-control" name="jumlah" value="{{ old('jumlah') }}" placeholder="Contoh: 200000" required min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="projek_id" class="form-label">Projek</label>
                        <select name="projek_id" class="form-select" required>
                        <option value="" disabled selected>Pilih Projek</option>
                            @foreach($projek as $item)
                                <option value="{{ $item->id }}" {{ old('projek_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_projek }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="coa_id" class="form-label">Akun</label>
                        <select name="coa_id" class="form-select" required>
                        <option value="" disabled selected>Pilih Akun</option>
                            @foreach($coa as $c)
                                <option value="{{ $c->id }}" {{ old('coa_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->kode_akun }} - {{ $c->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="evidence" class="form-label">Evidence (JPG, PDF)</label>
                        <input type="file" class="form-control" name="evidence" accept=".jpg,.jpeg,.pdf">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('KasKeluar.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeSelect = document.querySelector('select[name="tipe"]');
    const akunSelect = document.querySelector('select[name="coa_id"]');
    
    tipeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        
        // Bersihkan opsi saat ini
        akunSelect.innerHTML = '<option value="">Pilih Akun</option>';
        
        if (selectedType) {
            // Ambil akun berdasarkan tipe yang dipilih
            fetch(`/get-accounts-by-type/${encodeURIComponent(selectedType)}`)
                .then(response => response.json())
                .then(accounts => {
                    accounts.forEach(account => {
                        const option = document.createElement('option');
                        option.value = account.id;
                        option.textContent = `${account.kode_akun} - ${account.nama_akun}`;
                        akunSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error mengambil akun:', error));
        }
    });
});
</script>
@endsection