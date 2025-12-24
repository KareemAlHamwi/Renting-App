@extends('components.layout')

@section('content')
    <div class="user-header">

        <h2 class="username">{{ $user->username }}</h2>
        <span class="badge {{ $user->role == 1 ? 'badge-admin' : 'badge-user' }}" style="font-size: 1.25rem;">
            {{ $user->role == 1 ? 'Admin' : 'User' }}
        </span>

    </div>
    <div class="card">
        <div class="photo-section">
            @php

                $photoUrl = function (?string $path): string {
                    if (!$path) {
                        return asset('images/default.png');
                    }
                    if (str_contains($path, '://')) {
                        return $path;
                    }

                    $path = preg_replace('#^public/#', '', $path);
                    $path = ltrim($path, '/');

                    return str_starts_with($path, 'storage/') ? asset($path) : asset('storage/' . $path);
                };

                $person = $user->person ?? null;

                $personalSrc = $photoUrl($person?->personal_photo);
                $idSrc = $person?->id_photo ? $photoUrl($person->id_photo) : asset('images/id_card.png');
            @endphp

            <div class="photo-section">
                <div class="photo-item avatar-item">
                    <img src="{{ $personalSrc }}" alt="Profile Photo"
                        onerror="this.onerror=null;this.src='{{ asset('images/default.png') }}';">
                </div>

                <div class="photo-item id-item">
                    <img src="{{ $idSrc }}" alt="ID Card"
                        onerror="this.onerror=null;this.src='{{ asset('images/id_card.png') }}';">
                </div>
            </div>


        </div>
        <div class="user-data">
            <p><strong>Name:</strong> {{ $user->person->first_name }} {{ $user->person->last_name }}</p>

            <p><strong>Status:</strong>
                @if ($user->verified_at)
                    <span class="status verified">Verified</span>
                @else
                    <span class="status pending">Pending</span>
                @endif
            </p>
            <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
            <p><strong>Registered at:</strong> {{ $user->person->created_at->format('Y-m-d') }}</p>
        </div>

        <div class="card-footer">
            <div class="footer-left">
                <a href="{{ url('/users') }}" class="btn btn-secondary">Properties</a>
                <a href="{{ url('/users') }}" class="btn btn-secondary">Reservations</a>
            </div>

            <div class="footer-right">
                <a href="{{ url('/users') }}" class="btn btn-secondary">Back</a>
                <form action="{{ url('/users/' . $user->id . '/verify') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-primary verify-btn"
                        @if ($user->verified_at) disabled @endif>
                        {{ $user->verified_at ? 'Verified' : 'Verify' }}
                    </button>
                </form>

            </div>
        </div>

    </div>
@endsection
