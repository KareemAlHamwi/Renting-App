@extends('components.layout')

@section('content')
    <h2>User Details</h2>

    <div class="card">
        <img src="{{ asset('images/default.png') }}" alt="Photo" class="avatar-lg">

        <div class="card-body">
            <div class="user-data">
                <h3>{{ $user->username }}</h3>
                <p><strong>Name:</strong> {{ $user->person->first_name }} {{ $user->person->last_name }}</p>
                <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
                <p><strong>Role:</strong> {{ $user->role == 1 ? 'Admin' : 'User' }}</p>
                <p><strong>Status:</strong>
                    @if ($user->verified_at)
                        <span class="status verified">Verified</span>
                    @else
                        <span class="status pending">Pending</span>
                    @endif
                </p>
                <p><strong>Registered at:</strong> {{ $user->person->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ url('/users') }}" class="btn btn-secondary">Back to Users</a>
            <button class="btn btn-primary">Verify</button>
        </div>
    </div>
@endsection
