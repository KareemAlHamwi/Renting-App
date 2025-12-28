@props([
    // Pass the same collection you render in the table.
    'reservations' => collect(),
    'searchPlaceholder' => 'Search by username, name or date...',
    // The ID of the *reservations* table this filter should control.
    'tableId' => 'reservationsTable',
])

@php
    // Unique DOM namespace so this component can safely appear more than once on a page.
    $uid = 'res_filters_' . uniqid();

    // Use the canonical enum list + order.
    // Values are the enum labels (lowercase) so they match the rendered status text.
    $statusOptions = array_map(
        fn ($case) => $case->label(),
        \App\Enums\ReservationStatus::cases(),
    );
@endphp

<div class="search-form" id="{{ $uid }}">
    <input style="width: 4000px" type="text" id="{{ $uid }}_searchInput" placeholder="{{ $searchPlaceholder }}">

    <select style="width: 200px" id="{{ $uid }}_statusFilter">
        <option value="">All Statuses</option>
        @foreach ($statusOptions as $value)
            <option value="{{ $value }}">{{ ucfirst($value) }}</option>
        @endforeach
    </select>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const root = document.getElementById(@json($uid));
        if (!root) return;

        const searchInput = root.querySelector(@json('#' . $uid . '_searchInput'));
        const statusFilter = root.querySelector(@json('#' . $uid . '_statusFilter'));

        const table = document.getElementById(@json($tableId));
        const tbody = table?.querySelector('tbody');
        if (!tbody) return;

        function getText(el) {
            return (el?.textContent || "").toLowerCase().trim();
        }

        function filterTable() {
            const search = (searchInput?.value || "").toLowerCase().trim();
            const status = (statusFilter?.value || "").toLowerCase().trim();

            const rows = tbody.querySelectorAll("tr");
            rows.forEach(row => {
                if (row.querySelector("td[colspan]")) return;

                const landlordUsername = getText(row.querySelector("td:nth-child(1) strong"));
                const landlordName = getText(row.querySelector("td:nth-child(1) small"));

                const tenantUsername = getText(row.querySelector("td:nth-child(2) strong"));
                const tenantName = getText(row.querySelector("td:nth-child(2) small"));

                const startDate = getText(row.querySelector("td:nth-child(3)"));
                const endDate = getText(row.querySelector("td:nth-child(4)"));

                const statusText = getText(row.querySelector("td:nth-child(5) .status"));

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

        filterTable();
    });
</script>
