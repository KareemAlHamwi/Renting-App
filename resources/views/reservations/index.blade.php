@extends('components.layout')

@section('content')
    <div class="page-header flex-between">
        <h1>Reservations Management</h1>
        <p class="muted">View reservations</p>
    </div>

    <div class="search-form">
        <input style="width: 4000px" type="text" id="searchInput" placeholder="Search by username, name or date...">

        <select style="width: 200px" id="verifiedFilter">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="reserved">Reserved</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const statusFilter = document.getElementById("verifiedFilter"); // your select id
            const rows = document.querySelectorAll(".users-table tbody tr");

            function filterTable() {
                const search = (searchInput?.value || "").toLowerCase().trim();
                const status = (statusFilter?.value || "").toLowerCase().trim();

                rows.forEach(row => {
                    // landlord
                    const landlordUsername = row.querySelector("td:nth-child(1) strong")?.textContent
                        .toLowerCase() || "";
                    const landlordName = row.querySelector("td:nth-child(1) small")?.textContent
                        .toLowerCase() || "";

                    // tenant
                    const tenantUsername = row.querySelector("td:nth-child(2) strong")?.textContent
                        .toLowerCase() || "";
                    const tenantName = row.querySelector("td:nth-child(2) small")?.textContent
                    .toLowerCase() || "";

                    // dates
                    const startDate = row.querySelector("td:nth-child(3)")?.textContent.toLowerCase()
                    .trim() || "";
                    const endDate = row.querySelector("td:nth-child(4)")?.textContent.toLowerCase()
                    .trim() || "";

                    // status text (Pending / Reserved / Completed / Cancelled)
                    const statusText = row.querySelector("td:nth-child(5) span")?.textContent.toLowerCase()
                        .trim() || "";

                    const matchesSearch = !search ||
                        landlordUsername.includes(search) ||
                        landlordName.includes(search) ||
                        tenantUsername.includes(search) ||
                        tenantName.includes(search) ||
                        startDate.includes(search) ||
                        endDate.includes(search);

                    const matchesStatus = !status || statusText.includes(status);

                    row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
                });
            }

            searchInput?.addEventListener("input", filterTable);
            statusFilter?.addEventListener("change", filterTable);

            document.querySelectorAll(".clickable-row").forEach(row => {
                row.addEventListener("click", function() {
                    const reservationId = this.dataset.id;
                    window.location.href = `/reservations/${reservationId}`;
                });
            });
        });
    </script>


    <div class="card table-wrapper">
        <table class="users-table">
            <thead>
                <tr>
                    <th style="text-align: left">Landlord</th>
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

                        <td>{{ $reservation->start_date->format('Y-m-d') }}</td>
                        <td>{{ $reservation->end_date->format('Y-m-d') }}</td>

                        <td>
                            @php $status = $reservation->status; @endphp

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
@endsection
