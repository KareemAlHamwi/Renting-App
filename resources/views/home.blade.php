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
            <h4>Pending Verification</h4>
            <p class="stat-number text-warning">{{ $pendingUsers }}</p>
        </div>

        <div class="card stat-card">
            <h4>Admins</h4>
            <p class="stat-number">{{ $adminsCount }}</p>
        </div>
    </div>

    <h2>Properties</h2>
@endsection
