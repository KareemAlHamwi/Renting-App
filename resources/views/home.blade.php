@extends('components.layout')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Overview</h1>
            <p class="muted">System summary and recent activity</p>
        </div>
    </div>

    {{-- TOP STATS --}}
    <section class="dash-grid dash-grid--cards">
        <div class="card stat-card">
            <div class="stat-top">
                <h4>Total Properties</h4>
                <p class="stat-number">{{ $totalProperties }}</p>
            </div>
            <p class="stat-meta">{{ $verifiedProperties }} verified · {{ $pendingProperties }} pending</p>
        </div>

        <div class="card stat-card">
            <div class="stat-top">
                <h4>Active Reservations</h4>
                <p class="stat-number">{{ $pendingReservations + $approvedReservations }}</p>
            </div>
            <p class="stat-meta">{{ $pendingReservations }} pending · {{ $approvedReservations }} approved</p>
        </div>

        <div class="card stat-card">
            <div class="stat-top">
                <h4>Pending Approvals</h4>
                <p class="stat-number text-warning">{{ $pendingReservations }}</p>
            </div>
            <p class="stat-meta">Reservations awaiting landlord action</p>
        </div>

        <div class="card stat-card">
            <div class="stat-top">
                <h4>Average Rating</h4>
                <p class="stat-number">{{ number_format($avgRating, 1) }}</p>
            </div>
            <p class="stat-meta">Across all reviews</p>
        </div>

        <div class="card stat-card">
            <div class="stat-top">
                <h4>Total Users</h4>
                <p class="stat-number">{{ $totalUsers }}</p>
            </div>
            <p class="stat-meta">{{ $verifiedUsers }} verified · {{ $pendingUsers }} pending</p>
        </div>

        <div class="card stat-card">
            <div class="stat-top">
                <h4>Average Rent</h4>
                <p class="stat-number">{{ number_format($avgRent, 0) }}</p>
            </div>
            <p class="stat-meta">Platform-wide average</p>
        </div>
    </section>

    {{-- 2-COLUMN MAIN --}}
    <section class="dash-grid dash-grid--two">
        {{-- LEFT: RESERVATION OVERVIEW + RECENT --}}
        <div class="stack">
            <div class="card">
                <div class="card-head">
                    <h3>Reservation Overview</h3>
                    <p class="muted">Current status distribution</p>
                </div>

                <div class="kpi-list">
                    <div class="kpi-row">
                        <span class="kpi-label">Pending</span>
                        <span class="kpi-value text-warning">{{ $pendingReservations }}</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Reserved</span>
                        <span class="kpi-value text-success">{{ $approvedReservations }}</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Cancelled</span>
                        <span class="kpi-value">{{ $cancelledReservations }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <h3>Recent Reservations</h3>
                    <p class="muted">Last 5 activity</p>
                </div>

                <div class="list">
                    @forelse($recentReservations as $r)
                        <a class="list-item" href="/reservations/{{ $r->id }}">
                            <div class="list-main">
                                <div class="list-title">
                                    Reservation #{{ $r->id }}
                                    <span class="pill pill-{{ strtolower($r->status->name ?? (string) $r->status) }}">
                                        {{ $r->status->name ?? $r->status }}
                                    </span>
                                </div>
                                <div class="list-sub">
                                    {{ $r->start_date->format('Y-m-d') }} →
                                    {{ $r->end_date->format('Y-m-d') ?? $r->start_date->format('Y-m-d') }}
                                    · Property: {{ $r->property->title }}
                                    · User: {{ $r->user->username }}
                                </div>
                            </div>
                            <div class="list-right">View</div>
                        </a>
                    @empty
                        <p class="muted">No reservations yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT: PROPERTIES + NEEDS ATTENTION --}}
        <div class="stack">
            <div class="card">
                <div class="card-head">
                    <h3>Properties</h3>
                    <p class="muted">Quality indicators</p>
                </div>

                <div class="kpi-list">
                    <div class="kpi-row">
                        <span class="kpi-label">Verified</span>
                        <span class="kpi-value text-success">{{ $verifiedProperties }}</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Unverified</span>
                        <span class="kpi-value text-warning">{{ $pendingProperties }}</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Missing photos</span>
                        <span class="kpi-value">{{ $propertiesNoPhotos }}</span>
                    </div>
                </div>
            </div>

            <div class="card attention-card">
                <div class="card-head">
                    <h3>Needs Attention</h3>
                    <p class="muted">Items requiring action</p>
                </div>

                <ul class="attention-list">
                    <li>
                        <span class="attention-dot warn"></span>
                        <span>{{ $attention['pending_users'] }} users pending verification</span>
                        <a class="attention-link" href="/users">Open</a>
                    </li>

                    <li>
                        <span class="attention-dot warn"></span>
                        <span>{{ $attention['unverified_properties'] }} properties need verification</span>
                        <a class="attention-link" href="/properties">Open</a>
                    </li>

                    <li>
                        <span class="attention-dot"></span>
                        <span>{{ $attention['properties_no_photos'] }} properties missing photos</span>
                        <a class="attention-link" href="/properties">Open</a>
                    </li>

                    <li>
                        <span class="attention-dot warn"></span>
                        <span>{{ $attention['pending_reservations'] }} reservations pending approval</span>
                        <a class="attention-link" href="/reservations">Open</a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
@endsection
