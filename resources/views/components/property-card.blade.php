@props([
    'property',
    'showActions' => true,
    'backUrl' => url('/properties'),
    'reservationsUrl' => url('/reservations'),
    'showVerify' => true,
])

<div class="card">
    <div>
        <p>
            <strong>Description:</strong><br>
            {{ $property->description }}
        </p>
    </div>

    <div class="user-data">
        <p><strong>Address:</strong> {{ $property->address }}</p>

        <p>
            <strong>Status:</strong>
            @if ($property->verified_at)
                <span class="status verified">Verified</span>
            @else
                <span class="status pending">Pending</span>
            @endif
        </p>

        <p>
            <strong>Governorate:</strong>
            <span class="governorateName" data-gov-id="{{ $property->governorate_id }}">
                {{ $property->governorate_id }}
            </span>
        </p>

        <p><strong>Rent:</strong> {{ $property->rent }}</p>
    </div>

    @php
        $photos = $property->photos()->orderBy('order')->limit(5)->get();
    @endphp

    <div class="photo-section-property" aria-label="Property photos">
        <div class="photo-carousel" role="region" aria-label="Scrollable property photos">
            @forelse ($photos as $i => $photo)
                @php
                    $path = trim((string) $photo->path);
                    $isUrl = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//']);
                    $src = $isUrl ? $path : \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                @endphp

                <div class="photo-slide">
                    <img src="{{ $src }}" alt="Property photo {{ $i + 1 }}" loading="lazy"
                        onerror="this.onerror=null;this.src='{{ asset('images/property.jpg') }}';">
                </div>
            @empty
                <div class="photo-slide">
                    <img src="{{ asset('images/property.jpg') }}" alt="Property photo">
                </div>
            @endforelse
        </div>
    </div>

    @if ($showActions)
        <div class="card-footer">
            <div class="footer-left">
                <a href="{{ $reservationsUrl }}" class="btn btn-secondary">Reservations</a>
            </div>

            <div class="footer-right">
                <a href="{{ $backUrl }}" class="btn btn-secondary">Back</a>

                @if ($showVerify)
                    <form action="{{ url('/properties/' . $property->id . '/verify') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary verify-btn" @if ($property->verified_at) disabled @endif>
                            {{ $property->verified_at ? 'Verified' : 'Verify' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
