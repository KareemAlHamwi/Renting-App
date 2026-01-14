@props([
    'searchPlaceholder' => 'Search by username, name or phone number...',
])

@php
    $q = request('q', '');
    $verification = request('verification', '');
    $role = request('role', '');
    $activation = request('activation', '');
    $perPage = (int) request('per_page', 10);

    $perPageOptions = [10, 15, 20, 50];

    $openFilters = filled($verification) || filled($activation) || filled($role) || request()->has('per_page');
@endphp

<form class="search-form" method="GET" action="{{ url()->current() }}">
    <input class="search-input" type="text" name="q" value="{{ $q }}"
        placeholder="{{ $searchPlaceholder }}">

    <details class="filter-popover" @if ($openFilters) open @endif>
        <summary class="filter-btn">Filters</summary>

        <div class="popover-card">
            <div class="field">
                <label>Account</label>
                <select name="activation">
                    <option value="">All Accounts</option>
                    <option value="activated" @selected($activation === 'activated')>Activated</option>
                    <option value="deactivated" @selected($activation === 'deactivated')>Deactivated</option>
                </select>
            </div>

            <div class="field">
                <label>Verification</label>
                <select name="verification">
                    <option value="">All Status</option>
                    <option value="verified" @selected($verification === 'verified')>Verified</option>
                    <option value="pending" @selected($verification === 'pending')>Pending</option>
                </select>
            </div>

            <div class="field">
                <label>Role</label>
                <select name="role">
                    <option value="">All Roles</option>
                    <option value="user" @selected($role === 'user')>User</option>
                    <option value="admin" @selected($role === 'admin')>Admin</option>
                </select>
            </div>

            <div class="field">
                <label>Per page</label>
                <select name="per_page">
                    @foreach ($perPageOptions as $n)
                        <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }} users</option>
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
