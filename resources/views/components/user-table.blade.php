@props(['users', 'tableId' => 'usersTable', 'detailsBaseUrl' => '/users/'])

<div class="card table-wrapper">
        <table id="{{ $tableId }}" class="users-table">
            <thead>
                <tr>
                    <th style="text-align: left">User</th>
                    <th>Phone number</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Registered at</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="clickable-row" data-id="{{ $user->id }}">
                        <td>
                            <div class="user-info" style="display:flex; gap:12px; align-items:center;">
                                @php $person = $user->person; @endphp

                                @php
                                    $photo = $person?->personal_photo;

                                    if (!$photo) {
                                        $photoSrc = asset('images/default.png');
                                    } elseif (str_contains($photo, '://')) {
                                        // already a full URL (S3/CDN/etc)
                                        $photoSrc = $photo;
                                    } else {
                                        // normalize common stored formats:
                                        // "users/..." OR "public/users/..." OR "storage/users/..."
                                        $photo = preg_replace('#^public/#', '', $photo);
                                        $photo = ltrim($photo, '/');

                                        $photoSrc = str_starts_with($photo, 'storage/')
                                            ? asset($photo)
                                            : asset('storage/' . $photo);
                                    }
                                @endphp

                                <div>
                                @if ($user->deactivated_at)
                                <div class="status-dot offline-glow" title="Offline"></div>
                                @else
                                <div class="status-dot online-glow" title="Online"></div>
                                @endif
                            </div>

                                <img src="{{ $photoSrc }}" alt="Personal Photo" class="avatar-sm"
                                    onerror="this.onerror=null;this.src='{{ asset('images/default.png') }}';" />


                                <div>
                                    <strong>{{ $user->username }}</strong><br>
                                    <small>{{ $person?->first_name }} {{ $person?->last_name }}</small>
                                </div>
                            </div>
                        </td>

                        <td>{{ $user->phone_number }}</td>
                        <td>
                            <span class="badge {{ $user->role == 1 ? 'badge-admin' : 'badge-user' }}">
                                {{ $user->role == 1 ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td>
                            @if ($user->verified_at)
                                <span class="status verified">Verified</span>
                            @else
                                <span class="status pending">Pending</span>
                            @endif
                        </td>
                        <td>{{ $user->person->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<div>
    {{ $users->links() }}
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
