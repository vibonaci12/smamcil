@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Jadwal Mengajar Saya</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Hari</th>
                <th>Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $jadwal)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $jadwal->kelas->nama ?? '-' }}</td>
                <td>{{ $jadwal->mapel }}</td>
                <td>{{ $jadwal->hari }}</td>
                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 