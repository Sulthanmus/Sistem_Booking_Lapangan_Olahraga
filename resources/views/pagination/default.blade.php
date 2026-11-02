@if ($paginator->hasPages())
    <nav class="actions" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn-sm btn-ghost" style="opacity:.5">Sebelumnya</span>
        @else
            <a class="btn btn-sm btn-ghost" href="{{ $paginator->previousPageUrl() }}">Sebelumnya</a>
        @endif

        <span class="muted">Halaman {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a class="btn btn-sm btn-ghost" href="{{ $paginator->nextPageUrl() }}">Berikutnya</a>
        @else
            <span class="btn btn-sm btn-ghost" style="opacity:.5">Berikutnya</span>
        @endif
    </nav>
@endif
