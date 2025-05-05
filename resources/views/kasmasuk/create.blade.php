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
                <!-- <li class="nav-item">    
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)">    
                        <i class="ti ti-bell-ringing"></i>    
                        <div class="notification bg-primary rounded-circle"></div>    
                    </a>    
                </li>     -->
            </ul>    
            <div class="ms-auto me-3">
            <a href="{{url('logout')}}" class="btn btn-outline-primary">Logout</a>
          </div>    
        </nav>    
    </header>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Tambah Kas Masuk</h5>
                
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

                <form action="{{ route('kasmasuk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="no_kasmasuk" class="form-label">No Kas Masuk</label>
                        <input type="text" class="form-control" name="no_kasmasuk" value="{{ $noKasMasuk }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Penerimaan Hibah DAPTV" required>
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

                    <!-- <div class="mb-3">
                        <label for="tipe" class="form-label">Tipe</label>
                        <select name="tipe" id="tipe" class="form-select" required>
                            <option value="">Pilih Tipe</option>
                            <option value="operasional">Arus kas dari aktivitas operasional</option>
                            <option value="investasi">Arus kas dari aktivitas investasi</option>
                            <option value="pendanaan">Arus kas dari aktivitas pendanaan</option>
                        </select>
                    </div> -->
                    
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Nominal</label>
                        <input type="number" class="form-control" name="jumlah" value="{{ old('jumlah') }}" placeholder="Contoh: 10000000" required min="0">
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
                    <a href="{{ route('kasmasuk.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

// resources/views/kasmasuk/create.blade.php (atau file view Anda)

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    // Selector untuk dropdown tipe
    const tipeSelect = document.querySelector('select[name="tipe"]');
    // Selector untuk dropdown akun - sesuaikan dengan name di form Anda
    const akunSelect = document.querySelector('select[name="coa_id"]');
    
    if(tipeSelect && akunSelect) {
        tipeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            console.log('Tipe yang dipilih:', selectedType); // Debugging
            
            // Reset dropdown akun
            akunSelect.innerHTML = '<option value="">Pilih Akun</option>';
            
            if (selectedType) {
                // Ambil akun berdasarkan tipe yang dipilih
                fetch(`/get-accounts-by-type/${encodeURIComponent(selectedType)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(accounts => {
                        console.log('Akun yang diterima:', accounts); // Debugging
                        accounts.forEach(account => {
                            const option = document.createElement('option');
                            option.value = account.id;
                            option.textContent = `${account.kode_akun} - ${account.nama_akun}`;
                            akunSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error saat mengambil akun:', error);
                    });
            }
        });
    } else {
        console.error('Tidak dapat menemukan elemen select untuk tipe atau akun');
    }
});
</script> -->

// resources/views/kasmasuk/create.blade.php (atau file view Anda)

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