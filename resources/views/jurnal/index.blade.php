<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jurnal Mengajar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h2 class="mb-4">Data Jurnal Mengajar Guru</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <a href="/jurnal/create" class="btn btn-primary mb-3">
        + Tambah Data
    </a>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Kelas</th>
                <th>Materi</th>
                <th>Hadir</th>
                <th>Tidak Hadir</th>
                <th>Status Guru</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

       @foreach($jurnal as $item)

        <tr>

            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->id_guru }}</td>
            <td>{{ $item->id_kelas }}</td>
            <td>{{ $item->materi }}</td>
            <td>{{ $item->jumlah_siswa_hadir }}</td>
            <td>{{ $item->jumlah_siswa_tidak_hadir }}</td>
            <td>{{ $item->status_guru }}</td>
            <td>{{ $item->catatan }}</td>

            <td>
                <a href="#" class="btn btn-warning btn-sm">Edit</a>

                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

</div>

</body>
</html>