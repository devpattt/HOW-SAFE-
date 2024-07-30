
// Initialize the map, centered on an example area (adjust to your area)
var map = L.map('map').setView([14.5995, 120.9842], 13);

// Add a tile layer (OpenStreetMap tiles are free to use)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var barangays = [];

// Function to create a popup content
function createPopupContent(barangay) {
    return `
        <strong>${barangay.name}</strong><br>
        Traffic: ${barangay.traffic}<br>
        Incident Rate: ${barangay.incidentRate}<br>
        Safety: ${barangay.safety}
    `;
}

// Function to fetch data from PHP and update the map
function fetchData() {
    fetch('data.php')
        .then(response => response.json())
        .then(data => {
            barangays = data;
            updateMap(barangays);
        });
}

// Function to update the map with markers
function updateMap(data) {
    data.forEach(barangay => {
        L.marker([barangay.lat, barangay.lng]).addTo(map)
            .bindPopup(createPopupContent(barangay));
    });
}

// Initial fetch of data
fetchData();

// Handle form submission for feedback
document.getElementById('feedbackForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch('submit_feedback.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
          alert('Feedback submitted successfully');
          fetchData(); // Refresh data after submission
      });
});

// Handle form submission for filtering
document.getElementById('filterForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const filterData = new FormData(this);
    const filteredBarangays = barangays.filter(barangay => {
        const filterBarangay = filterData.get('filterBarangay').toLowerCase();
        const minTraffic = filterData.get('minTraffic') || 1;
        const maxTraffic = filterData.get('maxTraffic') || 5;
        const minIncidentRate = filterData.get('minIncidentRate') || 1;
        const maxIncidentRate = filterData.get('maxIncidentRate') || 5;
        const minSafety = filterData.get('minSafety') || 1;
        const maxSafety = filterData.get('maxSafety') || 5;
        return (!filterBarangay || barangay.name.toLowerCase().includes(filterBarangay)) &&
               barangay.traffic >= minTraffic && barangay.traffic <= maxTraffic &&
               barangay.incidentRate >= minIncidentRate && barangay.incidentRate <= maxIncidentRate &&
               barangay.safety >= minSafety && barangay.safety <= maxSafety;
    });
    updateMap(filteredBarangays);
});
