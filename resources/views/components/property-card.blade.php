@props([
    'property',
    'cardHeader' => 'Property Card',
    'showActions' => true,
    'backUrl' => url('/properties'),
    'reservationsUrl' => url('/reservations'),
])



<h2>{{ $cardHeader }}</h2>
<div class="card">
    <h1 class="username" style="margin-top: -12px">{{ $property->title }}</h1>

    <div>
        <p>
            <strong>Description:</strong><br>
            {{ $property->description }}
        </p>
    </div>

    <div class="property-data">
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
            <span>
                {{ $property->governorate->governorate_name }}
            </span>
        </p>

        <p><strong>Rent:</strong> {{ $property->rent }}</p>

        @if ($property->overall_reviews)
            @php
                $rating = (float) $property->overall_reviews; // 0.0 .. 5.0 step 0.5
                $full = (float) floor($rating); // 0..5
                $half = $rating - $full >= 0.5 ? 1 : 0; // 0 or 1
                $empty = 5 - $full - $half;
            @endphp

            <p class="stars-line">
                <strong>Stars:</strong>
                <span class="stars" aria-label="{{ number_format($rating, 1) }} out of 5">
                    <span class="stars-full">{!! str_repeat('★', $full) !!}</span>
                    @if ($half)
                        <span class="stars-half">★</span>
                    @endif
                    <span class="stars-empty">{!! str_repeat('☆', $empty) !!}</span>
                </span>
                <span class="stars-value">({{ number_format($rating, 1) }})</span>
            </p>

            <p class="review-line">
                <strong>Reviewers Number:</strong>
                <span>{{ $property->reviewers_number }}</span>
            </p>
        @endif
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

            <a href="{{ $backUrl }}" class="btn btn-secondary">Back</a>

            <form action="{{ url('/properties/' . $property->id . '/toggle') }}" method="POST" style="display:inline;"
                onsubmit="return confirm('{{ $property->published_at ? 'Are you sure you want to unpublish this property?' : 'Are you sure you want to publish this property?' }}');">
                @csrf
                <button type="submit"
                    class="btn btn-primary @if (!$property->published_at) btn-alert @else btn-safe @endif">
                    {{ $property->published_at ? 'Published' : 'Unpublished' }}
                </button>
            </form>

            <form action="{{ url('/properties/' . $property->id . '/verify') }}" method="POST"
                style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary" @if ($property->verified_at) disabled @endif>
                    {{ $property->verified_at ? 'Verified' : 'Verify' }}
                </button>
            </form>
        </div>
    @endif
</div>
