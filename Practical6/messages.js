const eventGrid=document.getElementById("eventGrid");
const search=document.getElementById("search");
const categoryFilter=document.getElementById("categoryFilter");
const sortBy=document.getElementById("sortBy");
const pagination=document.getElementById("pagination");
const loading=document.getElementById("loading");
const error=document.getElementById("error");

let allEvents=[];
let currentPage=1;

const eventsPerPage=6;


fetch("events.json")
    .then(function(response) {

        if(!response.ok) {
            throw new Error("Could not load events.json");
        }

        return response.json();

    })
    .then(function(events) {
        loading.style.display="none";

        allEvents=events;

        applyFilters();

    })
     .catch(function(err) {

        loading.style.display="none";

        error.textContent="Unable to load events.";

        console.log(err);

    });


function displayEvents(events) {

    eventGrid.innerHTML="";
    pagination.innerHTML="";


    if(events.length===0) {

        eventGrid.innerHTML="<p>No events found.</p>";

        return;
    }


    const startIndex=(currentPage-1)*eventsPerPage;

    const endIndex=startIndex+eventsPerPage;

    const pageEvents=events.slice(startIndex,endIndex);


    pageEvents.forEach(function(event) {

        const card=document.createElement("div");

        card.className="event-card";


        card.innerHTML=`

            <div class="event-image">

                <img src="${event.image}" alt="${event.name}">

            </div>


            <div class="event-content">

                <p class="event-date">${event.date}</p>

                <h2>${event.name}</h2>

                <p>${event.description}</p>

                <p>${event.location}</p>

                <a href="event.html" class="event-button">
                    Register->
                </a>

            </div>

        `;


        eventGrid.appendChild(card);

    });


    createPagination(events);
}


function createPagination(events) {

    pagination.innerHTML="";


    const totalPages=Math.ceil(events.length/eventsPerPage);


    for(let i=1;i<=totalPages;i++) {

        const button=document.createElement("button");

        button.textContent=i;


        if(i===currentPage) {

            button.className="active";

        }


        button.addEventListener("click",function() {

            currentPage=i;

            displayEvents(events);

        });


        pagination.appendChild(button);

    }

}


function applyFilters() {

    currentPage=1;


    const searchText=search.value.toLowerCase().trim();

    const selectedCategory=categoryFilter.value;

    const selectedSort=sortBy.value;


    let filteredEvents=allEvents.filter(function(event) {


        const matchesSearch=

            event.name.toLowerCase().includes(searchText) ||

            event.description.toLowerCase().includes(searchText) ||

            event.location.toLowerCase().includes(searchText);


        const matchesCategory=

            selectedCategory==="all" ||

            event.category===selectedCategory;


        return matchesSearch && matchesCategory;

    });


    if(selectedSort==="nameAsc") {

        filteredEvents.sort(function(a,b) {

            return a.name.localeCompare(b.name);

        });

    }


    if(selectedSort==="nameDesc") {

        filteredEvents.sort(function(a,b) {

            return b.name.localeCompare(a.name);

        });

    }


    if(selectedSort==="date") {

        filteredEvents.sort(function(a,b) {

            return new Date(a.date)-new Date(b.date);

        });

    }


    displayEvents(filteredEvents);

}


search.addEventListener("input",function() {

    applyFilters();

});


categoryFilter.addEventListener("change",function() {

    applyFilters();

});


sortBy.addEventListener("change",function() {

    applyFilters();

});