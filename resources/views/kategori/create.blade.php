<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori - Soto Mbak Rati</title>
</head>
<body>
    <h2>Tambah Kategori Baru</h2>
    
    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        <label>Nama Kategori:</label><br>
        <input type="text" name="nama_kategori" required placeholder="Makanan">
        <br><br>
        <button type="submit">Simpan Kategori</button>
    </form>
    
    <br>
    <a href="{{ route('kategori.index') }}">Kembali ke Daftar</a>
</body>
</html>