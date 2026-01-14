@props([
    'searchPlaceholder' => 'Search by title, governorate or address...',
    'govsUrl' => 'http://renting-app.test/api/governorates',
])

@php
    use Illuminate\Support\Facades\Http;

    $q       = request('q', '');
    $govId   = request('governorate_id', '');
    $status  = request('status', '');
    $publishment  = request('publishment', '');
    $perPage = (int) request('per_page', 10);
    $sortBy  = request('sort_by', 'id');
    $sortDir = request('sort_dir', 'desc');

    $perPageOptions = [10, 15, 20, 50];

    $sortByOptions = [
        'id' => 'Created (ID)',
        'rent' => 'Rent',
        'overall_reviews' => 'Ratings',
        'reviewers_number' => 'Reviewers Number',
        'verified_at' => 'Verified Date',
    ];

    $openFilters = filled($govId) || filled($publishment) || filled($status) || request()->has('per_page') || request()->has('sort_by') || request()->has('sort_dir');

    $govs = collect();
    try {
        $res = Http::timeout(5)->acceptJson()->get($govsUrl);
        if ($res->ok()) {
            $json = $res->json();
            $list = is_array($json) ? $json : ($json['data'] ?? []);

            $govs = collect($list)
                ->map(function ($g) {
                    $id = $g['id'] ?? null;
                    $label = $g['name'] ?? $g['title'] ?? $g['governorate_name'] ?? null;
                    if ($id === null) return null;

                    return [
                        'id' => (string) $id,
                        'label' => $label ? (string) $label : ('#' . $id),
                    ];
                })
                ->filter()
                ->values();
        }
    } catch (\Throwable $e) {

    }
@endphp

<form class="search-form" method="GET" action="{{ url()->current() }}">
    <input
        class="search-input"
        type="text"
        name="q"
        value="{{ $q }}"
        placeholder="{{ $searchPlaceholder }}"
    >

    <details class="filter-popover" @if($openFilters) open @endif>
        <summary class="filter-btn">Filters</summary>

        <div class="popover-card">
            <div class="field">
                <label>Governorate</label>
                <select name="governorate_id">
                    <option value="">All Governorates</option>

                    @foreach ($govs as $g)
                        <option value="{{ $g['id'] }}" @selected((string)$govId === (string)$g['id'])>
                            {{ $g['label'] }}
                        </option>
                    @endforeach

                    @if ($govs->isEmpty() && $govId !== '')
                        <option value="{{ $govId }}" selected>#{{ $govId }}</option>
                    @endif
                </select>
            </div>

            <div class="field">
                <label>Status</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="verified" @selected($status === 'verified')>Verified</option>
                    <option value="pending"  @selected($status === 'pending')>Pending</option>
                </select>
            </div>

            <div class="field">
                <label>Publishment</label>
                <select name="publishment">
                    <option value="">All Properties</option>
                    <option value="published" @selected($status === 'published')>Published</option>
                    <option value="unpublished"  @selected($status === 'unpublished')>Unpublished</option>
                </select>
            </div>

            <div class="field">
                <label>Per page</label>
                <select name="per_page">
                    @foreach ($perPageOptions as $n)
                        <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }} properties</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Sort by</label>
                <select name="sort_by">
                    @foreach ($sortByOptions as $key => $label)
                        <option value="{{ $key }}" @selected($sortBy === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Order</label>
                <select name="sort_dir">
                    <option value="desc" @selected($sortDir === 'desc')>Descending</option>
                    <option value="asc"  @selected($sortDir === 'asc')>Ascending</option>
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
