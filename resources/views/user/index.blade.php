<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Pengguna (User)</h2>
            <a href="#" class="btn btn-primary">+ Tambah User</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 100px;">No User</th>
                                <th>Nama Pengguna</th>
                                <th>Nama Jabatan</th>
                                <th style="width: 160px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $item-> {'no user'} }}</td>
                                    <td>{{ $item->{'nama pengguna'} }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $item->{'nama jabatan'} }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Data user belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>