document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search");
    const addForm = document.getElementById("addForm");

    loadList();

    searchInput.addEventListener("input", (e) => {
        loadList(e.target.value);
    });

    addForm.addEventListener("submit", async (e) => {
        e.preventDefault(); 
        hideError();

        const newRoom = {
            number: document.getElementById("addNumber").value,
            capacity: document.getElementById("addCapacity").value,
            price_per_night: document.getElementById("addPrice").value
        };

        try {
            const response = await fetch("api_add.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(newRoom)
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.error || "Помилка сервера");

            addForm.reset();
            loadList(searchInput.value); 
        } catch (error) {
            showError(error.message);
        }
    });
});

async function loadList(query = "") {
    hideError();
    try {
        const response = await fetch(`api_list.php?q=${encodeURIComponent(query)}`);
        if (!response.ok) throw new Error("Помилка завантаження списку");
        
        const data = await response.json();
        renderTable(data);
    } catch (error) {
        showError(error.message);
    }
}

function renderTable(data) {
    const resultsContainer = document.getElementById("results");
    resultsContainer.innerHTML = ""; 

    data.forEach(room => {
        const tr = document.createElement("tr");
        
        tr.innerHTML = `
            <td class="fw-bold">${escapeHTML(room.number)}</td>
            <td>${escapeHTML(String(room.capacity))} осіб</td>
            <td>${escapeHTML(String(room.price_per_night))} грн</td>
            <td>
                <button class="btn btn-sm btn-success" onclick="bookRoom(this)">Забронювати</button>
            </td>
        `;
        resultsContainer.appendChild(tr);
    });
}

function bookRoom(btn) {
    const row = btn.closest("tr");
    row.classList.add("table-secondary");
    btn.textContent = "Зайнято";
    btn.classList.replace("btn-success", "btn-secondary");
    btn.disabled = true;
}

function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function showError(msg) {
    const errorContainer = document.getElementById("error-message");
    errorContainer.textContent = msg;
    errorContainer.classList.remove("d-none");
}

function hideError() {
    document.getElementById("error-message").classList.add("d-none");
}