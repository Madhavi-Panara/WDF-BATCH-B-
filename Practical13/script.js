console.log("Practical 13 JavaScript is running!");
const apiURL = "api/events.php";

const eventGrid = document.getElementById("eventGrid");
const loading = document.getElementById("loading");
const message = document.getElementById("message");
const eventCount = document.getElementById("eventCount");
const emptyState = document.getElementById("emptyState");

let events = [];

async function loadEvents() {
    loading.style.display = "block";
    eventGrid.innerHTML = "";

    try {
        const response = await fetch(apiURL);
        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || "Unable to load events.");
        }

        events = result.data;
        console.log("Events received:", events.length);
        displayEvents();

    } catch (error) {
        message.textContent = "Error loading events. Please check your API.";
        message.style.display = "block";
    } finally {
        loading.style.display = "none";
    }
}

function displayEvents() {
    const search = document.getElementById("search").value.toLowerCase();
    const category = document.getElementById("categoryFilter").value;
    const status = document.getElementById("statusFilter").value;
    const sortBy = document.getElementById("sortBy").value;

    let filteredEvents = events.filter(event => {
        const matchesSearch =
            event.event_name.toLowerCase().includes(search) ||
            (event.location || "").toLowerCase().includes(search) ||
            (event.description || "").toLowerCase().includes(search);

        const matchesCategory =
            !category || event.category === category;

        const matchesStatus =
            !status || event.status === status;

        return matchesSearch && matchesCategory && matchesStatus;
    });

    console.log("Filtered events:", filteredEvents.length);
    
    if (sortBy === "name") {
        filteredEvents.sort((a, b) =>
            a.event_name.localeCompare(b.event_name)
        );
    } else if (sortBy === "date") {
        filteredEvents.sort((a, b) =>
            new Date(a.event_date) - new Date(b.event_date)
        );
    } else {
        filteredEvents.sort((a, b) =>
            Number(b.event_id) - Number(a.event_id)
        );
    }

    eventGrid.innerHTML = "";

    filteredEvents.forEach(event => {
        const card = document.createElement("div");
        card.className = "event-card";

        const title = document.createElement("h3");
        title.textContent = event.event_name;

        const date = document.createElement("p");
        date.textContent = "Date: " + event.event_date;

        const categoryText = document.createElement("p");
        categoryText.textContent = "Category: " + event.category;

        const location = document.createElement("p");
        location.textContent = "Location: " + (event.location || "Not specified");

        const description = document.createElement("p");
        description.textContent = event.description || "No description available.";

        const statusText = document.createElement("p");
        statusText.textContent = "Status: " + event.status;

        card.append(title, date, categoryText, location, description, statusText);
        eventGrid.appendChild(card);
    });

    eventCount.textContent = "Total events: " + filteredEvents.length;
    emptyState.style.display = filteredEvents.length ? "none" : "block";
}

document.getElementById("search").addEventListener("input", displayEvents);
document.getElementById("categoryFilter").addEventListener("change", displayEvents);
document.getElementById("statusFilter").addEventListener("change", displayEvents);
document.getElementById("sortBy").addEventListener("change", displayEvents);

loadEvents();
