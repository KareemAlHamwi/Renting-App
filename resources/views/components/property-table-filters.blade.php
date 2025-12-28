@props([
    'properties',
    'searchPlaceholder' => 'Search by title, governorate or address...',
    'govsUrl' => url('/api/governorates'),
    // The ID of the *properties* table this filter should control.
    'tableId' => 'propertiesTable',
])

@php
    // Unique DOM namespace so this component can safely appear more than once on a page.
    $uid = 'prop_filters_' . uniqid();
@endphp

<div class="search-form" id="{{ $uid }}">
    <input style="width: 4000px" type="text" id="{{ $uid }}_searchInput" placeholder="{{ $searchPlaceholder }}">

    <select style="width: 200px" id="{{ $uid }}_governorateFilter">
        <option value="">All Governorates</option>
        @foreach ($properties->pluck('governorate_id')->unique() as $govId)
            <option value="{{ $govId }}">#{{ $govId }}</option>
        @endforeach
    </select>

    <select style="width: 200px" id="{{ $uid }}_verifiedFilter">
        <option value="">All Statuses</option>
        <option value="verified">Verified</option>
        <option value="pending">Pending</option>
    </select>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const root = document.getElementById(@json($uid));
        if (!root) return;

        const searchInput = root.querySelector(@json('#' . $uid . '_searchInput'));
        const verifiedFilter = root.querySelector(@json('#' . $uid . '_verifiedFilter'));
        const governorateFilter = root.querySelector(@json('#' . $uid . '_governorateFilter'));

        const table = document.getElementById(@json($tableId));
        const tbody = table?.querySelector('tbody');
        if (!tbody) return;

        const GOVS_URL = @json($govsUrl);

        async function loadGovernorates() {
            try {
                const res = await fetch(GOVS_URL, { headers: { "Accept": "application/json" } });
                if (!res.ok) return null;

                const json = await res.json();
                const list = Array.isArray(json) ? json : (json.data || []);
                const map = {};

                list.forEach(g => {
                    const id = String(g.id);
                    const name = g.name ?? g.title ?? g.governorate_name ?? null;
                    if (id && name) map[id] = name;
                });

                return map;
            } catch (e) {
                return null;
            }
        }

        function applyGovernorateNames(govMap) {
            if (!govMap) return;

            // Update governorate names only inside the *target* properties table.
            table.querySelectorAll(".gov-cell").forEach(cell => {
                const id = cell.dataset.govId;
                if (id && govMap[id]) cell.textContent = govMap[id];
            });

            // Update dropdown labels for this filter instance.
            Array.from(governorateFilter.options).forEach(opt => {
                const id = opt.value;
                if (id && govMap[id]) opt.textContent = govMap[id];
            });
        }

        function filterTable() {
            const search = (searchInput?.value || "").toLowerCase().trim();
            const status = (verifiedFilter?.value || "").toLowerCase().trim();
            const selectedGovernorateId = (governorateFilter?.value || "").trim();

            const rows = tbody.querySelectorAll('tr');
            rows.forEach(row => {
                if (row.querySelector('td[colspan]')) return;

                const title = row.querySelector("td:nth-child(1) strong")?.textContent.toLowerCase() || "";

                const govCell = row.querySelector(".gov-cell");
                const governorateName = govCell?.textContent.toLowerCase().trim() || "";
                const governorateId = govCell?.dataset.govId?.trim() || "";

                const address = row.querySelector("td:nth-child(3)")?.textContent.toLowerCase() || "";
                const statusText = row.querySelector("td:nth-child(4) span")?.textContent.toLowerCase() || "";

                const matchesSearch = !search ||
                    title.includes(search) ||
                    governorateName.includes(search) ||
                    address.includes(search);

                const matchesStatus = !status || statusText.includes(status);
                const matchesGovernorate = !selectedGovernorateId || governorateId === selectedGovernorateId;

                row.style.display = (matchesSearch && matchesStatus && matchesGovernorate) ? "" : "none";
            });
        }

        searchInput?.addEventListener("input", filterTable);
        verifiedFilter?.addEventListener("change", filterTable);
        governorateFilter?.addEventListener("change", filterTable);

        loadGovernorates().then(map => {
            applyGovernorateNames(map);
            filterTable();
        });
    });
</script>
