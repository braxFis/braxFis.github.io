function addSorting() {
    const table = document.querySelector(".time-table table");
    const headers = table.querySelectorAll("th");
    const tbody = table.querySelector("tbody");

    headers.forEach((header, columnIndex) => {
        header.addEventListener("mouseover", function (){
            header.style.cursor = "pointer";
        });

        header.addEventListener("click", function () {
            const rows = Array.from(tbody.querySelectorAll("tr"));
            const isAscending = header.dataset.order === "asc";
            header.dataset.order = isAscending ? "desc" : "asc";

            rows.sort((rowA, rowB) => {
                const cellA = rowA.children[columnIndex].textContent.trim();
                const cellB = rowB.children[columnIndex].textContent.trim();

                if (!isNaN(cellA) && !isNaN(cellB)) {
                    return isAscending ? cellA - cellB : cellB - cellA;
                } else {
                    return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
                }
            });

            tbody.innerHTML = "";
            tbody.append(...rows);
        });
    });
}
