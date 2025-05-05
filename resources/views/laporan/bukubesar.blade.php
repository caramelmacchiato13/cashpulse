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
      <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buku Besar</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('bukubesar.getData') }}" method="GET" id="form-filter">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periode">Pilih Periode</label>
                                    <input type="month" class="form-control" id="periode" name="periode" value="{{ $periode ?? date('Y-m') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="akun">Pilih Akun</label>
                                    <select class="form-control" id="akun" name="akun">
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}" {{ isset($selectedAkun) && $selectedAkun == $a->id ? 'selected' : '' }}>
                                                {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="projek">Pilih Proyek</label>
                                    <select class="form-control" id="projek" name="projek">
                                        <option value="" disabled selected>Pilih Proyek</option>
                                        @foreach($projeks as $p)
                                            <option value="{{ $p->id }}" {{ isset($selectedProjek) && $selectedProjek == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_projek }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4">
                        <div class="text-center mb-3">
                            <h4>Buku Besar {{ isset($dataAkun) ? $dataAkun->nama_akun : 'Kas Tunai' }}</h4>
                            <h5>Periode {{ $periodeBulan ?? date('F Y') }}</h5>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Akun</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>Saldo Debet</th>
                                        <th>Saldo Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Saldo Awal -->
                                    <tr>
                                        <td>-</td>
                                        <td>Saldo Awal</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>Rp {{ isset($saldoAwal) ? number_format($saldoAwal, 0, ',', '.') : '0' }}</td>
                                        <td>-</td>
                                    </tr>
                                    
                                    <!-- Data Transaksi -->
                                    @if(isset($transaksi) && count($transaksi) > 0)
                                        @foreach($transaksi as $t)
                                        <tr>
                                            <td>{{ date('Y-m-d', strtotime($t->tanggal)) }}</td>
                                            <td>{{ isset($dataAkun) ? $dataAkun->nama_akun : 'Kas Tunai' }}</td>
                                            <td>{{ strtolower($t->akun_debit) === strtolower($dataAkun->nama_akun) ? 'Rp ' . number_format($t->debet, 0, ',', '.') : '-' }}</td>
                                            <td>{{ strtolower($t->akun_kredit) === strtolower($dataAkun->nama_akun) ? 'Rp ' . number_format($t->kredit, 0, ',', '.') : '-' }}</td>
                                            <td>Rp {{ number_format($t->saldo_debet, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($t->saldo_kredit, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data transaksi untuk periode dan akun yang dipilih</td>
                                        </tr>
                                    @endif
                                    
                                    <!-- Saldo Akhir -->
                                    <tr>
                                        <td>-</td>
                                        <td>Saldo Akhir</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>Rp {{ isset($saldoAkhir) ? number_format($saldoAkhir, 0, ',', '.') : '0' }}</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Akhir Proses Jurnal -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#akun, #projek').select2({
            placeholder: 'Pilih',
            allowClear: true
        });
    });
</script>
@endpush