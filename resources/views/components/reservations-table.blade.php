@props([
    'reservations',
    'tableId' => 'reservationsTable',
    // Base URL used for row navigation (kept here; filters should not handle navigation)
    'detailsBaseUrl' => '/reservations/',
])

<div class="card table-wrapper">
    <table id="{{ $tableId }}" class="users-table">
        <thead>
            <tr>
                <th style="text-align: left">Landlord</th>
                {{-- <th style="text-align: left">Property</th> --}}
                <th style="text-align: left">Tenant</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($reservations as $reservation)
                @php
                    $landlord = $reservation->property?->owner;
                    $landlordPerson = $landlord?->person;

                    $tenant = $reservation->user;
                    $tenantPerson = $tenant?->person;

                    $makePhotoSrc = function ($person) {
                        $photo = $person?->personal_photo;

                        if (!$photo) {
                            return asset('images/default.png');
                        }
                        if (str_contains($photo, '://')) {
                            return $photo;
                        }

                        $photo = preg_replace('#^public/#', '', $photo);
                        $photo = ltrim($photo, '/');

                        return str_starts_with($photo, 'storage/') ? asset($photo) : asset('storage/' . $photo);
                    };

                    $landlordPhotoSrc = $makePhotoSrc($landlordPerson);
                    $tenantPhotoSrc = $makePhotoSrc($tenantPerson);

                    $status = $reservation->status;
                @endphp

                <tr class="clickable-row" data-id="{{ $reservation->id }}">

                    <td>
                        <div class="user-info" style="display:flex; gap:12px; align-items:center;">
                            <img src="{{ $landlordPhotoSrc }}" alt="Landlord Photo" class="avatar-sm"
                                onerror="this.onerror=null;this.src='{{ asset('images/default.png') }}';" />
                            <div>
                                <strong>{{ $landlord?->username ?? '—' }}</strong><br>
                                <small>{{ $landlordPerson?->first_name }} {{ $landlordPerson?->last_name }}</small>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="user-info" style="display:flex; gap:12px; align-items:center;">
                            <img src="{{ $tenantPhotoSrc }}" alt="Tenant Photo" class="avatar-sm"
                                onerror="this.onerror=null;this.src='{{ asset('images/default.png') }}';" />
                            <div>
                                <strong>{{ $tenant?->username ?? '—' }}</strong><br>
                                <small>{{ $tenantPerson?->first_name }} {{ $tenantPerson?->last_name }}</small>
                            </div>
                        </div>
                    </td>

                    {{-- <td>
                        <div class="user-info">
                            @php
                                // Prefer first photo if already eager-loaded, otherwise fallback.
                                $firstPhoto = $reservation->property->photos->first();
                                $src = $firstPhoto
                                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($firstPhoto->path)
                                    : asset('images/property.jpg');
                            @endphp

                            <img src="{{ $src }}" alt="Property Image" class="avatar-sm avatar-square"
                                 onerror="this.onerror=null;this.src='{{ asset('images/property.jpg') }}';">

                            <div>
                                <strong>{{ $reservation->property->title }}</strong><br>
                                <small>{{ \Illuminate\Support\Str::limit($reservation->property->description, 60, '...') }}</small>
                            </div>
                        </div>
                    </td> --}}

                    <td>{{ $reservation->start_date->format('Y-m-d') }}</td>
                    <td>{{ $reservation->end_date->format('Y-m-d') }}</td>

                    <td>
                        @if ($status === \App\Enums\ReservationStatus::Pending)
                            <span class="status pending">Pending</span>
                        @elseif ($status === \App\Enums\ReservationStatus::Reserved)
                            <span class="status verified">Reserved</span>
                        @elseif ($status === \App\Enums\ReservationStatus::Completed)
                            <span class="status completed">Completed</span>
                        @elseif ($status === \App\Enums\ReservationStatus::Cancelled)
                            <span class="status cancelled">Cancelled</span>
                        @else
                            <span class="status pending">{{ (string) $status }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No reservations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const table = document.getElementById(@json($tableId));
        if (!table) return;

        const base = @json(rtrim($detailsBaseUrl, '/') . '/');

        table.querySelectorAll("tbody tr.clickable-row").forEach(row => {
            row.addEventListener("click", function(e) {
                // Don't hijack clicks on interactive elements inside the row.
                if (e.target.closest('a, button, input, select, textarea, label')) return;
                if (window.getSelection && window.getSelection().toString().length) return;

                const id = this.dataset.id;
                if (!id) return;

                window.location.href = `${base}${id}`;
            });
        });
    });
</script>
