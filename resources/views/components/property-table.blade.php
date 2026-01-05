@props([
'properties',
'tableId' => 'propertiesTable',
'detailsBaseUrl' => '/properties/',
'links' => true
])

<div class="card table-wrapper">
    <table id="{{ $tableId }}" class="users-table">
        <thead>
            <tr>
                <th style="text-align: left">Property</th>
                <th>Governorate</th>
                <th>Address</th>
                <th>Status</th>
                <th>Rent</th>
                <th>Ratings</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($properties as $property)
                <tr class="clickable-row" data-id="{{ $property->id }}">
                    <td>
                        <div class="user-info">
                            @php
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

                    <td>
                        @php
                            $govName =
                                $property->governorate->name ??
                                ($property->governorate->title ?? ($property->governorate->governorate_name ?? null));
                        @endphp
                        {{ $govName ?? '#' . $property->governorate_id }}
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
                    <td>
                        @if ($property->overall_reviews)
                            {{ $property->overall_reviews }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No properties found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div>
    @if ($links)

    {{ $properties->links() }}
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const table = document.getElementById(@json($tableId));
        if (!table) return;

        const base = @json(rtrim($detailsBaseUrl, '/') . '/');

        table.querySelectorAll("tbody tr.clickable-row").forEach(row => {
            row.addEventListener("click", function(e) {
                if (e.target.closest('a, button, input, select, textarea, label')) return;
                if (window.getSelection && window.getSelection().toString().length) return;

                const id = this.dataset.id;
                if (!id) return;

                window.location.href = `${base}${id}`;
            });
        });
    });
</script>
