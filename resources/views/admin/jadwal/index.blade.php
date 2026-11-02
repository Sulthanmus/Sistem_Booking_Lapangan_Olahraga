@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Kelola Jadwal</h1>
        <p class="muted">Atur slot waktu lapangan.</p>
    </div>
    <a href="{{ route('admin.jadwal.create') }}" class="btn">Tambah Jadwal</a>
</div>

<form class="filters" method="GET" style="margin-bottom:1rem">
    <label>Lapangan
        <select name="id_lapangan">
            <option value="">Semua</option>
            @foreach($lapangans as $l)
                <option value="{{ $l->id_lapangan }}" @selected(request('id_lapangan')==$l->id_lapangan)>{{ $l->nama_lapangan }}</option>
            @endforeach
        </select>
    </label>
    <label>Tanggal
        <input type="date" name="tanggal" value="{{ request('tanggal') }}">
    </label>
    <button class="btn btn-sm" type="submit">Filter</button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $jadwal->lapangan->nama_lapangan }}</td>
                    <td>{{ $jadwal->tanggal->format('d/m/Y') }}</td>
                    <td>{{ substr($jadwal->jam_mulai,0,5) }}–{{ substr($jadwal->jam_selesai,0,5) }}</td>
                    <td>
                        <span class="badge {{ $jadwal->status==='tersedia'?'badge-ok':'badge-danger' }}">{{ $jadwal->status }}</span>
                    </td>
                    <td class="actions">
                        <a class="btn btn-sm btn-ghost" href="{{ route('admin.jadwal.edit', $jadwal) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.jadwal.destroy', $jadwal) }}" onsubmit="return confirm('Hapus jadwal?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Belum ada jadwal.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $jadwals->links() }}</div>
@endsection
