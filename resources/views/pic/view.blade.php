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
        <div class="container-fluid">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h5 class="card-title fw-semibold mb-4">PIC</h5>
                  <div class="card">

                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Master Data PIC</h6>
                            
                            <!-- Tombol Tambah Data -->
                            <a href="{{ url('/pic/create') }}" class="btn btn-primary btn-icon-split btn-sm">
                                <span class="icon text-white-50">
                                    <i class="ti ti-plus"></i>
                                </span>
                                <span class="text">Tambah Data</span>
                            </a>
                            <!-- Akghir Tombol Tambah Data -->

                        </div>

                    <div class="card-body">
                      <!-- Awal Dari Tabel -->
                    <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Kode PIC</th>
                                            <th>Nama PIC</th>
                                            <th>Email</th>
                                            <th>No Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="thead-dark">
                                        <tr>
                                            <th>Kode PIC</th>
                                            <th>Nama PIC</th>
                                            <th>Email</th>
                                            <th>No Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach ($pic as $p)
                                        <tr>
                                            <td>{{ $p->kode_pic }}</td>
                                            <td>{{ $p->nama_pic }}</td>
                                            <td>{{ $p->email }}</td>
                                            <td>{{ $p->no_telp }}</td>
                                            <td>
                                                    <a href="{{ route('pic.edit', $p->id) }}" class="btn btn-success btn-icon-split btn-sm">
                                                        <span class="icon text-white-50">
                                                            <i class="ti ti-check"></i>
                                                        </span>
                                                        <span class="text">Ubah</span>
                                                    </a>

                                                    <a href="#" 
                                                      onclick="deleteConfirm(this); return false;" 
                                                      data-id="{{ $p->id }}" 
                                                      data-nama="{{ $p->nama_pic }}" 
                                                      class="btn btn-danger btn-icon-split btn-sm">
                                                        <span class="icon text-white-50">
                                                            <i class="ti ti-minus"></i>
                                                        </span>
                                                        <span class="text">Hapus</span>
                                                    </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                    <!-- Akhir Dari Tabel -->
                    </div>
                  </div>
                </div>
                
                
              </div>
            </div>
          </div>
        </div>


        <script>
    function deleteConfirm(e){
        var tomboldelete = document.getElementById('btn-delete');
        id = e.getAttribute('data-id');
        
        // Get the name of the item being deleted
        var nama = e.getAttribute('data-nama');

        var url3 = "{{url('pic/destroy/')}}";
        var url4 = url3.concat("/",id);
        tomboldelete.setAttribute("href", url4);

        var pesan = "Data <b>" + nama + "</b> akan dihapus";
        document.getElementById("xid").innerHTML = pesan;

        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'), {  keyboard: false });
        
        myModal.show();
    }
</script>

<!-- Logout Delete Confirmation-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
            <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                x
            </button>
        </div>
        <div class="modal-body" id="xid"></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
            <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>
        </div>
        </div>
    </div>
</div>   

@endsection