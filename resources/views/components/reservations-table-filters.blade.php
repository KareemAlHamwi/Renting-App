@props([
    'searchPlaceholder' => 'Search by username, name or date...',
    'detailsBaseUrl' => '/reservations/', // used by row click
])

<div class="search-form">
    <input style="width: 4000px" type="text" id="searchInput" placeholder="{{ $searchPlaceholder }}">

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
        const statusFilter = document.getElementById("verifiedFilter");
        const tbody = document.querySelector(".users-table tbody");
        if (!tbody) return;

        const detailsBaseUrl = @json($detailsBaseUrl);

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

        tbody.querySelectorAll(".clickable-row").forEach(row => {
            row.addEventListener("click", function(e) {
                if (window.getSelection && window.getSelection().toString().length) return;

                const reservationId = this.dataset.id;
                if (!reservationId) return;

                window.location.href = `${detailsBaseUrl}${reservationId}`;
            });
        });
    });
</script>
