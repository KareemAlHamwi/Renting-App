@props([
    'user',
    'cardHeader' => 'User Card',
    'showActions' => true,
    'backUrl' => url('/users'),
    'propertiesUrl' => url('/users'),
    'reservationsUrl' => url('/reservations'),
])

    <h2 >{{ $cardHeader }}</h2>


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

<div class="card">
    <div class="photo-section-person">

        <!-- LEFT COLUMN -->
        <div class="person-left">

            <!-- Top row: avatar + username/badge -->
            <div class="person-top">
                <div class="photo-item avatar-item">
                    <img src="{{ $personalSrc }}" alt="Profile Photo"
                        onerror="this.onerror=null;this.src='{{ asset('images/default.png') }}';">
                </div>

                <div class="user-header">
                    <h2 class="username">{{ $user->username }}</h2>
                    <span class="badge {{ $user->role == 1 ? 'badge-admin' : 'badge-user' }}">
                        {{ $user->role == 1 ? 'Admin' : 'User' }}
                    </span>
                </div>
            </div>

            <!-- Under them: user-data -->
            <div class="user-data">
                <p><strong>Name:</strong> {{ $person?->first_name }} {{ $person?->last_name }}</p>
                <p><strong>Phone:</strong> {{ $user->phone_number }}</p>

                <p><strong>Status:</strong>
                    @if ($user->verified_at)
                        <span class="status verified">Verified</span>
                    @else
                        <span class="status pending">Pending</span>
                    @endif
                </p>

                <p><strong>Registered at:</strong>
                    {{ $person?->created_at ? $person->created_at->format('Y-m-d') : '—' }}
                </p>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="photo-item id-item">
            <img src="{{ $idSrc }}" alt="ID Card"
                onerror="this.onerror=null;this.src='{{ asset('images/id_card.png') }}';">
        </div>

    </div>

    @if ($showActions)
        <div class="card-footer">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Back</a>

                <form action="{{ url('/users/' . $user->id . '/toggle') }}" method="POST"
                style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary @if (!$user->deactivated_at) btn-safe @else btn-alert @endif">
                    {{ $user->deactivated_at ? 'Deactivated' : 'Activated' }}
                </button>
            </form>

                <form action="{{ url('/users/' . $user->id . '/verify') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary verify-btn"
                        @if ($user->verified_at) disabled @endif>
                        {{ $user->verified_at ? 'Verified' : 'Verify' }}
                    </button>
                </form>
        </div>
    @endif
</div>
