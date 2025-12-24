@extends('components.layout')

@section('content')
    <div class="page-header">
        <h1>Dashboard Overview</h1>
        <p class="muted">System summary and recent activity</p>
    </div>

    <h2>Users</h2>
    <div class="user-data">
        <div class="card stat-card">
            <h4>Total Users</h4>
            <p class="stat-number">{{ $totalUsers }}</p>
        </div>

        <div class="card stat-card">
            <h4>Verified Users</h4>
            <p class="stat-number text-success">{{ $verifiedUsers }}</p>
        </div>

        <div class="card stat-card">
            <h4>Pending Users</h4>
            <p class="stat-number text-warning">{{ $pendingUsers }}</p>
        </div>

        <div class="card stat-card">
            <h4>Admins</h4>
            <p class="stat-number">{{ $adminsCount }}</p>
        </div>
    </div>

    <h2>Properties</h2>
    <div class="user-data">
        <div class="card stat-card">
            <h4>Total Properties</h4>
            <p class="stat-number">{{ $totalProperties }}</p>
        </div>

        <div class="card stat-card">
            <h4>Verified Properties</h4>
            <p class="stat-number text-success">{{ $verifiedProperties }}</p>
        </div>

        <div class="card stat-card">
            <h4>Pending Properties</h4>
            <p class="stat-number text-warning">{{ $pendingProperties }}</p>
        </div>

        <div class="card stat-card">
            <h4>Average Rent</h4>
            <p class="stat-number">{{ number_format($avgRent, 0) }}</p>
        </div>
    </div>

    <h2>Reservations</h2>
@endsection
