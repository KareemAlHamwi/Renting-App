document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const roleFilter = document.getElementById("roleFilter");
    const verifiedFilter = document.getElementById("verifiedFilter");
    const rows = document.querySelectorAll(".users-table tbody tr");

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const role = roleFilter.value;
        const verified = verifiedFilter.value;

        rows.forEach(row => {
            const username = row.querySelector("td:nth-child(2) strong")?.textContent.toLowerCase() || "";
            const phone = row.querySelector("td:nth-child(3)")?.textContent.toLowerCase() || "";
            const roleText = row.querySelector("td:nth-child(4) span")?.textContent || "";
            const verifiedText = row.querySelector("td:nth-child(5) span")?.textContent || "";

            let matchesSearch = username.includes(search) || phone.includes(search);
            let matchesRole = !role || roleText === role;
            let matchesVerified = !verified || verifiedText === verified;

            if (matchesSearch && matchesRole && matchesVerified) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener("input", filterTable);
    roleFilter.addEventListener("change", filterTable);
    verifiedFilter.addEventListener("change", filterTable);
});
