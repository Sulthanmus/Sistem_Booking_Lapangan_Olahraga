@extends('layouts.app')

@section('title', 'Kelola Pelanggan')

@section('content')
<div class="toolbar">
    <div>
        <h1 class="page-title">Kelola Pelanggan</h1>
        <p class="muted">Data pengguna dengan role pelanggan.</p>
    </div>
    <form class="filters" method="GET">
        <label>Cari
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama / email">
        </label>
        <button class="btn btn-sm" type="submit">Cari</button>
    </form>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Booking</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggans as $p)
                <tr>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->no_telepon ?: '—' }}</td>
                    <td>{{ $p->bookings_count }}</td>
                    <td class="actions">
                        <a class="btn btn-sm btn-ghost" href="{{ route('admin.pelanggan.show', $p) }}">Detail</a>
                        <a class="btn btn-sm btn-ghost" href="{{ route('admin.pelanggan.edit', $p) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.pelanggan.destroy', $p) }}" onsubmit="return confirm('Hapus pelanggan?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">Tidak ada pelanggan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $pelanggans->links() }}</div>
@endsection
