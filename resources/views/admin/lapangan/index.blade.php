@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Kelola Lapangan</h1>
        <p class="muted">CRUD data lapangan olahraga.</p>
    </div>
    <a href="{{ route('admin.lapangan.create') }}" class="btn">Tambah Lapangan</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Harga/Jam</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lapangans as $lapangan)
                <tr>
                    <td>{{ $lapangan->nama_lapangan }}</td>
                    <td>{{ $lapangan->jenis_lapangan }}</td>
                    <td>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</td>
                    <td>{{ $lapangan->lokasi }}</td>
                    <td>
                        <span class="badge {{ $lapangan->status==='aktif'?'badge-ok':'badge-danger' }}">{{ $lapangan->status }}</span>
                    </td>
                    <td class="actions">
                        <a class="btn btn-sm btn-ghost" href="{{ route('admin.lapangan.edit', $lapangan) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.lapangan.destroy', $lapangan) }}" onsubmit="return confirm('Hapus lapangan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
