@extends('layoutbootstrap')

@section('konten')
<div class="body-wrapper">
    <!-- Header section remained the same -->
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
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Filter Form -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form method="GET" action="{{ route('laporan.aruskas') }}" class="mb-4">
                                        <div class="container">
                                            <div class="row mb-3">
                                                <div class="col-sm-3">Pilih Periode</div>
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <select name="bulan" class="form-control">
                                                                @for($i = 1; $i <= 12; $i++)
                                                                    <option value="{{ $i }}" {{ request('bulan', date('m')) == $i ? 'selected' : '' }}>
                                                                        {{ Carbon\Carbon::create()->month($i)->format('F') }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select name="tahun" class="form-control">
                                                                @for($i = 2020; $i <= date('Y'); $i++)
                                                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <!-- <div class="col-md-4">
                                                            <select name="projek_id" class="form-control">
                                                                <option value="">Semua Projek</option>
                                                                @foreach($projeks as $projek)
                                                                    <option value="{{ $projek->id }}" {{ request('projek_id') == $projek->id ? 'selected' : '' }}>
                                                                        {{ $projek->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div> -->
                                                        <div class="col-md-4">
                                                            <select name="projek_id" class="form-control" required>
                                                                <option value="">Pilih Proyek</option>
                                                                @foreach($projeks as $projek)
                                                                    <option value="{{ $projek->id }}" {{ request('projek_id') == $projek->id ? 'selected' : '' }}>
                                                                        {{ $projek->nama_projek }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 text-end">
                                                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Laporan Arus Kas -->
<div class="card">
    <div class="card-body">
        <div class="text-center mb-4">
            <h3>COE Smart Electric Vehicle</h3>
            <h4>Laporan Arus Kas</h4>
            @if(request('projek_id'))
                <h5>{{ $selectedProjek->nama_projek }}</h5>
                <h6>Periode berakhir {{ $periode->format('F Y') }}</h6>
            @else
                <h6>Silakan pilih periode dan projek untuk menampilkan data</h6>
            @endif
        </div>
        @if(request('projek_id'))
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <!-- Aktivitas Operasi -->
                        <tr>
                            <td colspan="3"><strong>Arus kas dari aktivitas operasional:</strong></td>
                        </tr>
                        @if($kasmasuk_operasional_rinci->count() > 0)
                            <tr>
                                <td style="padding-left: 20px;">Pemasukan:</td>
                                <td colspan="2"></td>
                            </tr>
                            @foreach($kasmasuk_operasional_rinci as $km)
                            <tr>
                                <td style="padding-left: 40px;">{{ $km->keterangan }}</td>
                                <td>Rp</td>
                                <td class="text-end">{{ number_format($km->total) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        @if($kaskeluar_operasional_rinci->count() > 0)
                            <tr>
                                <td style="padding-left: 20px;">Pengeluaran:</td>
                                <td colspan="2"></td>
                            </tr>
                            @foreach($kaskeluar_operasional_rinci as $kk)
                            <tr>
                                <td style="padding-left: 40px;">{{ $kk->keterangan }}</td>
                                <td>-Rp</td>
                                <td class="text-end">{{ number_format($kk->total) }}</td>
                            </tr>
                            @endforeach
                        @endif
                        
                        <tr>
                            <td colspan="2"><strong>Arus kas bersih dari aktivitas operasional</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($kasmasuk_operasional - $kaskeluar_operasional) }}</strong></td>
                        </tr>

                        <!-- Aktivitas Investasi -->
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td colspan="3"><strong>Arus kas dari aktivitas investasi:</strong></td>
                        </tr>
                        @if($kasmasuk_investasi_rinci->count() > 0)
                            <tr>
                                <td style="padding-left: 20px;">Pemasukan:</td>
                                <td colspan="2"></td>
                            </tr>
                            @foreach($kasmasuk_investasi_rinci as $km)
                            <tr>
                                <td style="padding-left: 40px;">{{ $km->keterangan }}</td>
                                <td>Rp</td>
                                <td class="text-end">{{ number_format($km->total) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        @if($kaskeluar_investasi_rinci->count() > 0)
                            <tr>
                                <td style="padding-left: 20px;">Pengeluaran:</td>
                                <td colspan="2"></td>
                            </tr>
                            @foreach($kaskeluar_investasi_rinci as $kk)
                            <tr>
                                <td style="padding-left: 40px;">{{ $kk->keterangan }}</td>
                                <td>-Rp</td>
                                <td class="text-end">{{ number_format($kk->total) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        <tr>
                            <td colspan="2"><strong>Arus kas bersih dari aktivitas investasi</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($kasmasuk_investasi - $kaskeluar_investasi) }}</strong></td>
                        </tr>

                        <!-- Aktivitas Pendanaan -->
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td colspan="3"><strong>Arus kas dari aktivitas pendanaan:</strong></td>
                        </tr>
                        @if($kasmasuk_pendanaan_rinci->count() > 0)
                            <tr>
                                <td style="padding-left: 20px;">Pemasukan:</td>
                                <td colspan="2"></td>
                            </tr>
                            @foreach($kasmasuk_pendanaan_rinci as $km)
                            <tr>
                                <td style="padding-left: 40px;">{{ $km->keterangan }}</td>
                                <td>Rp</td>
                                <td class="text-end">{{ number_format($km->total) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        @if($kaskeluar_pendanaan_rinci->count() > 0)
                            <tr>
                                <td style="padding-left: 20px;">Pengeluaran:</td>
                                <td colspan="2"></td>
                            </tr>
                            @foreach($kaskeluar_pendanaan_rinci as $kk)
                            <tr>
                                <td style="padding-left: 40px;">{{ $kk->keterangan }}</td>
                                <td>-Rp</td>
                                <td class="text-end">{{ number_format($kk->total) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        <tr>
                            <td colspan="2"><strong>Arus kas bersih dari aktivitas pendanaan</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($kasmasuk_pendanaan - $kaskeluar_pendanaan) }}</strong></td>
                        </tr>

                        <!-- Ringkasan Arus Kas -->
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td colspan="2"><strong>Kas dan setara kas awal periode</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($kas_awal) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Kenaikan (penurunan) bersih kas dan setara kas</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($total_perubahan) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Kas dan setara kas akhir periode</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($kas_akhir) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection