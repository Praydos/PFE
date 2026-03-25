@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();

    // Build smart page window around current page
    $window = [];

    if ($last <= 7) {
        // Few pages — show all
        $window = range(1, $last);
    } else {
        $window[] = 1;

        $from = max(2, $current - 1);
        $to   = min($last - 1, $current + 1);

        if ($from > 2) $window[] = '...';

        foreach (range($from, $to) as $p) $window[] = $p;

        if ($to < $last - 1) $window[] = '...';

        $window[] = $last;
    }

    $btnBase    = 'display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--r-sm);font-size:.8rem;font-weight:500;text-decoration:none;border:1px solid var(--border);background:var(--bg-card);color:var(--text-secondary);cursor:pointer;';
    $btnActive  = 'display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--r-sm);font-size:.8rem;font-weight:700;text-decoration:none;border:1px solid var(--blue);background:var(--blue);color:#fff;box-shadow:var(--shadow-blue);';
    $btnDisabled = 'display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--r-sm);border:1px solid var(--border);background:var(--bg-card);color:var(--text-hint);cursor:not-allowed;';
@endphp

<nav style="display:flex;align-items:center;justify-content:center;gap:.3rem;flex-wrap:wrap;">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="{{ $btnDisabled }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="{{ $btnBase }}"
           onmouseover="this.style.background='var(--blue-light)';this.style.color='var(--blue)';this.style.borderColor='var(--blue-mid)'"
           onmouseout="this.style.background='var(--bg-card)';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
    @endif

    {{-- Page numbers --}}
    @foreach ($window as $page)
        @if ($page === '...')
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:.8rem;color:var(--text-muted);">…</span>
        @elseif ($page == $current)
            <span style="{{ $btnActive }}">{{ $page }}</span>
        @else
            <a href="{{ $paginator->url($page) }}" style="{{ $btnBase }}"
               onmouseover="this.style.background='var(--blue-light)';this.style.color='var(--blue)';this.style.borderColor='var(--blue-mid)'"
               onmouseout="this.style.background='var(--bg-card)';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">
                {{ $page }}
            </a>
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="{{ $btnBase }}"
           onmouseover="this.style.background='var(--blue-light)';this.style.color='var(--blue)';this.style.borderColor='var(--blue-mid)'"
           onmouseout="this.style.background='var(--bg-card)';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    @else
        <span style="{{ $btnDisabled }}">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
    @endif

</nav>
@endif