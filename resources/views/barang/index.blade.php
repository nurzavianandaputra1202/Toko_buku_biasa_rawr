<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Barang</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Barang
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
                            <th>No Barang</th>
                            <th>Jenis Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            @php
                                $hargaBarang = $item->harga ?? $item->{'harga barang'} ?? $item->{'harga satuan'} ?? 0;
                                $jenisRaw = strtolower(trim($item->{'jenis barang'} ?? ''));
                                $kodeJenis = match (true) {
                                    $jenisRaw === 'b' || str_contains($jenisRaw, 'buku')  => 'B',
                                    $jenisRaw === 'n' || str_contains($jenisRaw, 'novel') => 'N',
                                    $jenisRaw === 'k' || str_contains($jenisRaw, 'komik') => 'K',
                                    $jenisRaw === 'a' || str_contains($jenisRaw, 'alat')  => 'A',
                                    default => 'B',
                                };
                            @endphp
                            <tr>
                                <td>{{ $item->{'no barang'} }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $item->{'jenis barang'} }}</span>
                                </td>
                                <td>{{ $item->{'nama barang'} }}</td>
                                <td>{{ $item->{'nama satuan'} }}</td>
                                <td>Rp {{ number_format($hargaBarang, 0, ',', '.') }}</td>
                                <td>
                                    @if (strtolower($item->status) == 'aktif')
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
                                        data-id="{{ $item->{'no barang'} }}"
                                        data-jenis="{{ $kodeJenis }}"
                                        data-nama="{{ $item->{'nama barang'} }}"
                                        data-namasatuan="{{ $item->{'nama satuan'} }}"
                                        data-harga="{{ $hargaBarang }}"
                                        data-status="{{ strtolower($item->status) == 'aktif' ? '1' : '0' }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('barang.destroy', $item->{'no barang'}) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data barang belum tersedia.</td>
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
        <form action="{{ route('barang.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" maxlength="45" class="form-control" placeholder="Masukkan nama barang" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="B">Buku (B)</option>
                        <option value="N">Novel (N)</option>
                        <option value="K">Komik (K)</option>
                        <option value="A">Alat Tulis (A)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="idsatuan" class="form-select" required>
                        <option value="">-- Pilih Satuan --</option>
                        @foreach ($satuan as $s)
                            <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" min="0" class="form-control" placeholder="Contoh: 15000" required>
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
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" id="edit_nama" maxlength="45" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="jenis" id="edit_jenis" class="form-select" required>
                        <option value="B">Buku (B)</option>
                        <option value="N">Novel (N)</option>
                        <option value="K">Komik (K)</option>
                        <option value="A">Alat Tulis (A)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select name="idsatuan" id="edit_idsatuan" class="form-select" required>
                        @foreach ($satuan as $s)
                            <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" id="edit_harga" min="0" class="form-control" required>
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
            document.getElementById('edit_nama').value = this.dataset.nama;
            document.getElementById('edit_jenis').value = this.dataset.jenis;
            document.getElementById('edit_harga').value = this.dataset.harga;
            document.getElementById('edit_status').value = this.dataset.status;

            // Cocokkan nama satuan dari v_barang dengan opsi di dropdown
            const namaSatuan = (this.dataset.namasatuan || '').trim().toLowerCase();
            const selectSatuan = document.getElementById('edit_idsatuan');
            for (let opt of selectSatuan.options) {
                if (opt.text.trim().toLowerCase() === namaSatuan) {
                    selectSatuan.value = opt.value;
                    break;
                }
            }

            document.getElementById('formEdit').action = '/barang/' + id;
        });
    });
</script>
</body>
</html>