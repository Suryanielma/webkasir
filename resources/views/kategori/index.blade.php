<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kategori - Soto Mbak Rati</title>
</head>
<body>
    <h1>Daftar Kategori Soto</h1>
    
    <a href="{{ route('kategori.create') }}">
        <button type="button">+ Tambah Kategori Baru</button>
    </a>

    <hr>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $k)
                <tr>
                    <td>{{ $k->id_kategori }}</td>
                    <td>{{ $k->nama_kategori }}</td>
                    <td>
                        <button>Edit</button>
                        <button>Hapus</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>