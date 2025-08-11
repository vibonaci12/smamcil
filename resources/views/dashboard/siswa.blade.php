@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Dashboard Siswa</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 md:col-span-2">
            <div class="font-semibold text-lg mb-2">Jadwal Mata Pelajaran</div>
            @if($jadwalMingguan && count($jadwalMingguan))
                <div class="overflow-x-auto">
                <table class="table-auto w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-2 py-1">Hari</th>
                            <th class="px-2 py-1">Jam</th>
                            <th class="px-2 py-1">Mata Pelajaran</th>
                            <th class="px-2 py-1">Guru</th>
                            <th class="px-2 py-1">Kelas</th>
                            <th class="px-2 py-1">Materi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwalMingguan as $jadwal)
                        <tr>
                            <td class="border px-2 py-1">{{ $jadwal->hari }}</td>
                            <td class="border px-2 py-1">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td class="border px-2 py-1">{{ $jadwal->mapel }}</td>
                            <td class="border px-2 py-1">{{ $jadwal->guru->nama ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $jadwal->kelas->nama ?? '-' }}</td>
                            <td class="border px-2 py-1">
                                @if($jadwal->materis && count($jadwal->materis))
                                    @foreach($jadwal->materis as $materi)
                                        <a href="{{ asset('storage/'.$materi->file) }}" class="text-blue-600 underline" target="_blank">{{ $materi->judul }}</a><br>
                                    @endforeach
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @else
                <div class="text-gray-500">Tidak ada jadwal tersedia.</div>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="font-semibold text-lg mb-2">Pengumuman Terbaru</div>
            @if($pengumumanTerbaru && count($pengumumanTerbaru))
                <ul class="space-y-2">
                    @foreach($pengumumanTerbaru as $pengumuman)
                        <li class="border-b pb-2">
                            <div class="font-semibold">{{ $pengumuman->judul }}</div>
                            <div class="text-sm text-gray-500 mb-1">{{ $pengumuman->tanggal }}</div>
                            <div>{{ $pengumuman->isi }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-gray-500">Belum ada pengumuman terbaru.</div>
            @endif
        </div>
    </div>
</div>
@endsection 