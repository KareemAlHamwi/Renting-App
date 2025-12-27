@props(['properties'])

<div class="card table-wrapper">
    <table class="users-table">
        <thead>
            <tr>
                <th style="text-align: left">Property</th>
                <th>Governorate</th>
                <th>Address</th>
                <th>Status</th>
                <th>Rent</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($properties as $property)
                <tr class="clickable-row" data-id="{{ $property->id }}">
                    <td>
                        <div class="user-info">
                            @php
                                // Prefer first photo if already eager-loaded, otherwise fallback.
                                $firstPhoto = $property->photos->first();
                                $src = $firstPhoto
                                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($firstPhoto->path)
                                    : asset('images/property.jpg');
                            @endphp

                            <img src="{{ $src }}" alt="Property Image" class="avatar-sm avatar-square"
                                 onerror="this.onerror=null;this.src='{{ asset('images/property.jpg') }}';">

                            <div>
                                <strong>{{ $property->title }}</strong><br>
                                <small>{{ \Illuminate\Support\Str::limit($property->description, 60, '...') }}</small>
                            </div>
                        </div>
                    </td>

                    <td class="gov-cell" data-gov-id="{{ $property->governorate_id }}">
                        {{ $property->governorate_id ?? '—' }}
                    </td>

                    <td>{{ $property->address }}</td>

                    <td>
                        @if ($property->verified_at)
                            <span class="status verified">Verified</span>
                        @else
                            <span class="status pending">Pending</span>
                        @endif
                    </td>

                    <td>{{ $property->rent }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No properties found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
