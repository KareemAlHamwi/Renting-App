@props([
    'searchPlaceholder' => 'Search by username, name...',
])

@php
    $q = request('q', '');
    $status = request('status', '');
    $dateRange = request('date_range', '');
    $perPage = (int) request('per_page', 10);

    $perPageOptions = [10, 15, 20, 50];

    $dateRangeOptions = [
        '' => 'Any time',
        'today' => 'Today',
        'last_week' => 'Last week',
        'last_month' => 'Last month',
        'last_3_months' => 'Last 3 months',
        'last_6_months' => 'Last 6 months',
        'last_year' => 'Last year',
    ];

    $statusOptions = [
        '' => 'All Statuses',
        'pending' => 'Pending',
        'reserved' => 'Reserved',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    $openFilters = filled($status) || filled($dateRange) || request()->has('per_page');
@endphp

<form class="search-form" method="GET" action="{{ url()->current() }}">
    <input class="search-input" type="text" name="q" value="{{ $q }}"
        placeholder="{{ $searchPlaceholder }}">

    <details class="filter-popover" @if ($openFilters) open @endif>
        <summary class="filter-btn">Filters</summary>

        <div class="popover-card">
            <div class="field">
                <label>Date range</label>
                <select name="date_range">
                    @foreach ($dateRangeOptions as $value => $label)
                        <option value="{{ $value }}" @selected($dateRange === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Status</label>
                <select name="status">
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Per page</label>
                <select name="per_page">
                    @foreach ($perPageOptions as $n)
                        <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }}/page
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="popover-actions">
                <button type="submit" class="btn-apply">Apply</button>
                <a class="btn-reset" href="{{ url()->current() }}">Reset</a>
            </div>
        </div>
    </details>

    <button type="submit" class="btn-search">Search</button>
</form>
