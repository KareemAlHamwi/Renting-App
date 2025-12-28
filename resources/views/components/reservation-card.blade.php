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

    <div class="user-data" >
        <p><strong>Start date:</strong> {{ $reservation->start_date->format('Y-m-d') }}</p>
        <p><strong>End date:</strong> {{ $reservation->end_date->format('Y-m-d') }}</p>
        <p><strong>Residnets number:</strong> {{ $reservation->residents_number }}</p>
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
        @if ($reservation->review === null)
        <p><strong>Review:</strong> {{ $reservation->review }}</p>
        <p><strong>Stars:</strong> {{ $reservation->stars }}</p>

        @endif
    </div>

    @if ($showActions)
        <div class="card-footer">
            <a href="{{ $backUrl }}" class="btn btn-secondary">Back</a>
            @if ($showCancel)
                <form action="{{ url('/reservations/' . $reservation->id . '/cancel') }}" method="POST"
                    style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary verify-btn"
                        @if ($reservation->status === \App\Enums\ReservationStatus::Cancelled || $reservation->status === \App\Enums\ReservationStatus::Completed) disabled @endif>
                        {{ $reservation->status === \App\Enums\ReservationStatus::Cancelled || $reservation->status === \App\Enums\ReservationStatus::Completed ? 'Cancelled' : 'Cancel' }}
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>
