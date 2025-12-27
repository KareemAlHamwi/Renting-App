@extends('components.layout')

@section('content')
    <div class="page-header flex-between">
        <h1>Users Management</h1>
        <p class="muted">Verify and remove users</p>
    </div>

    <div class="search-form">
        <input style="width: 4000px" type="text" id="searchInput" placeholder="Search by username, name or phone number ...">

        <select style="width: 200px" id="roleFilter">
            <option value="">All Roles</option>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>

        <select style="width: 200px" id="verifiedFilter">
            <option value="">All Statuses</option>
            <option value="Pending">Pending</option>
            <option value="Verified">Verified</option>
        </select>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const roleFilter = document.getElementById("roleFilter");
            const verifiedFilter = document.getElementById("verifiedFilter");
            const rows = document.querySelectorAll(".users-table tbody tr");

            function filterTable() {
                const search = searchInput.value.toLowerCase().trim();
                const role = roleFilter.value.toLowerCase().trim();
                const verified = verifiedFilter.value.toLowerCase().trim();

                rows.forEach(row => {
                    const username = row.querySelector("td:nth-child(1) strong")?.textContent
                        .toLowerCase() || "";
                    const fullName = row.querySelector("td:nth-child(1) small")?.textContent
                        .toLowerCase() || "";
                    const phone = row.querySelector("td:nth-child(2)")?.textContent.toLowerCase() || "";
                    const roleText = row.querySelector("td:nth-child(3) span")?.textContent.toLowerCase()
                        .trim() || "";
                    const verifiedText = row.querySelector("td:nth-child(4) span")?.textContent
                        .toLowerCase().trim() || "";

                    // Case-insensitive partial matches
                    let matchesSearch = !search || username.includes(search) || phone.includes(search) ||
                        fullName.includes(search);
                    let matchesRole = !role || roleText.includes(role);
                    let matchesVerified = !verified || verifiedText.includes(verified);

                    row.style.display = (matchesSearch && matchesRole && matchesVerified) ? "" : "none";
                });
            }

            searchInput.addEventListener("input", filterTable);
            roleFilter.addEventListener("change", filterTable);
            verifiedFilter.addEventListener("change", filterTable);

            document.querySelectorAll(".clickable-row").forEach(row => {
                row.addEventListener("click", function() {
                    const userId = this.dataset.id;
                    window.location.href = `/users/${userId}`;
                });
            });
        });
    </script>

    <div class="card table-wrapper">
        <table class="users-table">
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

    {{-- <div class="pagination">
        {{ $users->links() }}
    </div> --}}
@endsection
