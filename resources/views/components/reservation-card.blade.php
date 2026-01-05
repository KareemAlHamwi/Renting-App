@props([
    'reservation',
    'cardHeader' => 'Reservation Card',
    'showActions' => true,
    'backUrl' => url('/reservations'),
    'reservationsUrl' => url('/reservations'),
    'showCancel' => true,
])



<h2>{{ $cardHeader }}</h2>
<div class="card">

    <div class="user-data">
        <p><strong>Start date:</strong> {{ $reservation->start_date->format('Y-m-d') }}</p>
        <p><strong>End date:</strong> {{ $reservation->end_date->format('Y-m-d') }}</p>
        {{-- <p><strong>Residnets number:</strong> {{ $reservation->residents_number }}</p> --}}
        <p><strong>status:</strong>
            @if ($reservation->status === \App\Enums\ReservationStatus::Pending)
                <span class="status pending">Pending</span>
            @elseif ($reservation->status === \App\Enums\ReservationStatus::Reserved)
                <span class="status verified">Reserved</span>
            @elseif ($reservation->status === \App\Enums\ReservationStatus::Completed)
                <span class="status completed">Completed</span>
            @elseif ($reservation->status === \App\Enums\ReservationStatus::Cancelled)
                <span class="status cancelled">Cancelled</span>
            @else
                <span class="status pending">{{ (string) $reservation->status }}</span>
            @endif
        </p>

        @if ($reservation->cancelled_by)
            <p><strong>Cancelled by:</strong> {{ $reservation->cancelledBy->username }}</p>
        @endif

        @if ($reservation->review)
            @php
                $rating = (float) $reservation->review->rating; // 0.0 .. 5.0 step 0.5
                $full = (int) floor($rating); // 0..5
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
                <strong>Review:</strong>
                <span>{{ $reservation->review->comment }}</span>
            </p>
        @endif


    </div>

    @if ($showActions)
        <div class="reservation-card-footer">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Back</a>
            @if ($showCancel)
                <form action="{{ url('/reservations/' . $reservation->id . '/cancel') }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @php
    // Force enum from raw DB value (bypasses any accessor overriding the cast)
    $statusEnum = $reservation->status instanceof \App\Enums\ReservationStatus
        ? $reservation->status
        : \App\Enums\ReservationStatus::tryFrom((int) $reservation->getRawOriginal('status'));

    $isCompleted = $statusEnum === \App\Enums\ReservationStatus::Completed;
    $isCancelled = $statusEnum === \App\Enums\ReservationStatus::Cancelled;

    $cancelDisabled = $isCompleted || $isCancelled;

    $cancelLabel = $isCompleted
        ? 'Completed'
        : ($isCancelled ? 'Cancelled' : 'Cancel');
@endphp

<button type="submit"
        class="btn btn-primary btn-alert"
        {{ $cancelDisabled ? 'disabled' : '' }}>
    {{ $cancelLabel }}
</button>

                </form>
            @endif
        </div>
    @endif
</div>
