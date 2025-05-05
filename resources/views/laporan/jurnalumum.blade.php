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
                    <h5>Data Jurnal Umum</h5>
                </div>
                <div class="card-body">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="input-group">
                <span class="input-group-text">Pilih Periode</span>
                <input type="month" class="form-control" id="periode" name="periode" value="{{ date('Y-m') }}">
                
                <span class="input-group-text">Pilih Proyek</span>
                <select class="form-select" id="pilihProjek" name="projek_id">
                    <option value="" disabled selected>Pilih Proyek</option>
                    @foreach($projekList as $projek)
                        <option value="{{ $projek->id }}">{{ $projek->nama_projek }}</option>
                    @endforeach
                </select>
                
                <button class="btn btn-primary" id="btnTampilkan" type="button">Tampilkan</button>
            </div>
        </div>
    </div>
    
    <div id="loadingIndicator" class="text-center d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Memuat data...</p>
    </div>
    
    <div id="errorMessage" class="alert alert-danger d-none">
        Error akan ditampilkan di sini
    </div>
    
    <div class="table-responsive mt-3">
    <!-- New project name display -->
    <h4 class="text-center mt-5">Jurnal Umum</h4>
    <h5 class="text-center mt-3" id="judulProyek"></h5>
    <h5 class="text-center mt-3" id="periodeTerpilih">Periode: {{ date('F Y') }}</h5>
    <table class="table table-bordered table-hover" id="tabelJurnalUmum">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Ref</th>
                    <th>Debet</th>
                    <th>Kredit</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi melalui AJAX -->
                <tr id="noDataRow">
                    <td colspan="6" class="text-center">Belum ada data</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th class="text-end" id="totalDebet">Rp 0</th>
                    <th class="text-end" id="totalKredit">Rp 0</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tambahkan ini untuk memberitahu browser bahwa kita mengirim CSRF token di setiap AJAX request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Fungsi untuk format angka sebagai mata uang
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }
        
        // Fungsi untuk format tanggal
        function formatTanggal(tanggal) {
            const date = new Date(tanggal);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).split('/').join('-');
        }
        
        // Fungsi untuk mengambil data jurnal umum
        function getJurnalUmum() {
            // Tampilkan loading indicator
            $('#loadingIndicator').removeClass('d-none');
            
            // Sembunyikan pesan error jika ada
            $('#errorMessage').addClass('d-none');
            
            const periode = $('#periode').val() || "{{ date('Y-m') }}";
            const projekId = $('#pilihProjek').val();
            const projekName = $('#pilihProjek option:selected').text();
            
            const periodeArray = periode.split('-');
            const bulan = new Date(periodeArray[0], periodeArray[1] - 1, 1).toLocaleDateString('id-ID', { month: 'long' });
            const tahun = periodeArray[0];
            
            // Update project name display
            if (projekId) {
                $('#judulProyek').text(projekName).removeClass('d-none');
            } else {
                $('#judulProyek').text('').addClass('d-none');
            }
            
            $('#periodeTerpilih').text('Periode: ' + bulan + ' ' + tahun);
            
            $.ajax({
                url: "{{ route('jurnal.umum.data') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    periode: periode,
                    projek_id: projekId
                },
                dataType: "json",
                success: function(response) {
                    // Sembunyikan loading indicator
                    $('#loadingIndicator').addClass('d-none');
                    
                    console.log("Response:", response);
                    
                    if (response.success) {
                        const data = response.data;
                        
                        if (data.length > 0) {
                            let html = '';
                            let totalDebet = 0;
                            let totalKredit = 0;
                           
                            data.forEach(function(item) {
                                html += '<tr>';
                                
                                html += '<td>' + formatTanggal(item.tanggal) + '</td>';
                                
                                // Akun Debit atau Kredit
                                if (item.akun_debit) {
                                    html += '<td>' + item.akun_debit + '</td>';
                                } else {
                                    html += '<td style="padding-left: 30px;">' + item.akun_kredit + '</td>';
                                }
                                
                                html += '<td>' + item.ref + '</td>';
                                
                                // Debet
                                if (item.debet > 0) {
                                    html += '<td class="text-end">' + formatRupiah(item.debet) + '</td>';
                                    totalDebet += parseFloat(item.debet);
                                } else {
                                    html += '<td></td>';
                                }
                                
                                // Kredit
                                if (item.kredit > 0) {
                                    html += '<td class="text-end">' + formatRupiah(item.kredit) + '</td>';
                                    totalKredit += parseFloat(item.kredit);
                                } else {
                                    html += '<td></td>';
                                }
                                
                                html += '<td>' + item.keterangan + '</td>';
                                html += '</tr>';
                            });
                           
                            $('#tabelJurnalUmum tbody').html(html);
                            $('#totalDebet').text(formatRupiah(totalDebet));
                            $('#totalKredit').text(formatRupiah(totalKredit));
                        } else {
                            $('#tabelJurnalUmum tbody').html('<tr><td colspan="6" class="text-center">Tidak ada data untuk periode yang dipilih</td></tr>');
                            $('#totalDebet').text(formatRupiah(0));
                            $('#totalKredit').text(formatRupiah(0));
                        }
                    } else {
                        // Tampilkan pesan error
                        $('#errorMessage').removeClass('d-none').text('Gagal mengambil data: ' + response.message);
                        $('#tabelJurnalUmum tbody').html('<tr><td colspan="6" class="text-center">Terjadi kesalahan saat mengambil data</td></tr>');
                        $('#totalDebet').text(formatRupiah(0));
                        $('#totalKredit').text(formatRupiah(0));
                    }
                },
                error: function(xhr, status, error) {
                    // Sembunyikan loading indicator
                    $('#loadingIndicator').addClass('d-none');
                    
                    console.error("AJAX Error:", xhr, status, error);
                    console.error("Response Text:", xhr.responseText);
                    
                    // Tampilkan pesan error
                    let errorMsg = 'Terjadi kesalahan pada server';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    $('#errorMessage').removeClass('d-none').text('Error: ' + errorMsg);
                    $('#tabelJurnalUmum tbody').html('<tr><td colspan="6" class="text-center">Gagal memuat data</td></tr>');
                    $('#totalDebet').text(formatRupiah(0));
                    $('#totalKredit').text(formatRupiah(0));
                }
            });
        }
        
        // Event listener untuk tombol Tampilkan
        $('#btnTampilkan').on('click', function() {
            console.log("Tombol Tampilkan diklik");
            getJurnalUmum();
        });
        
        // Load data jurnal umum saat halaman dimuat
        getJurnalUmum();
    });
</script>
@endsection