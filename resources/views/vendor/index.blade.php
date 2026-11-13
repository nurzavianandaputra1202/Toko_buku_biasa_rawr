<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Vendor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Vendor</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Vendor
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No Vendor</th>
                            <th>Nama Vendor</th>
                            <th>Status Hukum</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            @php
                                $kodeHukum = match($item->{'status hukum'}) {
                                    'PT' => 'P',
                                    'CV' => 'C',
                                    'UD' => 'U',
                                    default => '',
                                };
                            @endphp
                            <tr>
                                <td>{{ $item->{'no vendor'} }}</td>
                                <td>{{ $item->{'nama vendor'} }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $item->{'status hukum'} }}</span>
                                </td>
                                <td>
                                    @if ($item->status == 'aktif')
                                        <span class="badge bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-warning btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit"
                                        data-id="{{ $item->{'no vendor'} }}"
                                        data-nama="{{ $item->{'nama vendor'} }}"
                                        data-badan_hukum="{{ $kodeHukum }}"
                                        data-status="{{ $item->status == 'aktif' ? '1' : '0' }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('vendor.destroy', $item->{'no vendor'}) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data vendor ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data vendor belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('vendor.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Vendor</label>
                    <input type="text" name="nama_vendor" maxlength="100" class="form-control" placeholder="Masukkan nama vendor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Hukum</label>
                    <select name="badan_hukum" class="form-select" required>
                        <option value="P">PT (Perseroan Terbatas)</option>
                        <option value="C">CV (Commanditaire Vennootschap)</option>
                        <option value="U">UD (Usaha Dagang)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Non Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Vendor</label>
                    <input type="text" name="nama_vendor" id="edit_nama_vendor" maxlength="100" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Hukum</label>
                    <select name="badan_hukum" id="edit_badan_hukum" class="form-select" required>
                        <option value="P">PT (Perseroan Terbatas)</option>
                        <option value="C">CV (Commanditaire Vennootschap)</option>
                        <option value="U">UD (Usaha Dagang)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Non Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            document.getElementById('edit_nama_vendor').value = this.dataset.nama;
            document.getElementById('edit_badan_hukum').value = this.dataset.badan_hukum;
            document.getElementById('edit_status').value = this.dataset.status;
            document.getElementById('formEdit').action = '/vendor/' + id;
        });
    });
</script>
</body>
</html>