@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Absensi Siswa</h1>
    <a href="{{ route('absensi.create') }}" class="btn btn-primary mb-3">Tambah Absensi</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Siswa</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensis as $absensi)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $absensi->jadwal->kelas->nama ?? '-' }}</td>
                <td>{{ $absensi->jadwal->mapel ?? '-' }}</td>
                <td>{{ $absensi->siswa->nama ?? '-' }}</td>
                <td>{{ $absensi->tanggal }}</td>
                <td>{{ $absensi->status }}</td>
                <td>{{ $absensi->keterangan }}</td>
                <td>
                    <a href="{{ route('absensi.edit', $absensi) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('absensi.destroy', $absensi) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 