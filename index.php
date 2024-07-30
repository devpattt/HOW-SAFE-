<!DOCTYPE html>
<html>
<head>
    <title>How Safe - San Agustin, Novaliches</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
        #sidebar {
            float: left;
            width: 25%;
            padding: 10px;
        }
        #content {
            float: right;
            width: 70%;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <h1>How Safe - San Agustin, Novaliches</h1>
    <div id="sidebar">
        <h2>Submit Feedback</h2>
        <form id="feedbackForm">
            <label for="traffic">Traffic:</label><br>
            <input type="number" id="traffic" name="traffic" min="1" max="5" required><br><br>
            <label for="incidentRate">Incident Rate:</label><br>
            <input type="number" id="incidentRate" name="incidentRate" min="1" max="5" required><br><br>
            <label for="safety">Safety:</label><br>
            <input type="number" id="safety" name="safety" min="1" max="5" required><br><br>
            <input type="submit" value="Submit">
        </form>

        <h2>Filter and Search</h2>
        <form id="filterForm">
            <label for="minTraffic">Min Traffic:</label><br>
            <input type="number" id="minTraffic" name="minTraffic" min="1" max="5"><br><br>
            <label for="maxTraffic">Max Traffic:</label><br>
            <input type="number" id="maxTraffic" name="maxTraffic" min="1" max="5"><br><br>
            <label for="minIncidentRate">Min Incident Rate:</label><br>
            <input type="number" id="minIncidentRate" name="minIncidentRate" min="1" max="5"><br><br>
            <label for="maxIncidentRate">Max Incident Rate:</label><br>
            <input type="number" id="maxIncidentRate" name="maxIncidentRate" min="1" max="5"><br><br>
            <label for="minSafety">Min Safety:</label><br>
            <input type="number" id="minSafety" name="minSafety" min="1" max="5"><br><br>
            <label for="maxSafety">Max Safety:</label><br>
            <input type="number" id="maxSafety" name="maxSafety" min="1" max="5"><br><br>
            <input type="submit" value="Filter">
        </form>
    </div>
    <div id="content">
        <div id="map"></div>
    </div>
    <div class="clear"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize the map, centered on San Agustin, Novaliches, Quezon City
        var map = L.map('map').setView([14.731000, 121.046500], 14);

        // Add a tile layer (OpenStreetMap tiles are free to use)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var barangayData = [];

        // Function to create a popup content
        function createPopupContent(barangay) {
            return `
                <strong>San Agustin, Novaliches</strong><br>
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
                    barangayData = data;
                    updateMap(barangayData);
                });
        }

        // Function to update the map with markers
        function updateMap(data) {
            data.forEach(barangay => {
                L.marker([14.731000, 121.046500]).addTo(map)
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
            const filteredData = barangayData.filter(barangay => {
                const minTraffic = filterData.get('minTraffic') || 1;
                const maxTraffic = filterData.get('maxTraffic') || 5;
                const minIncidentRate = filterData.get('minIncidentRate') || 1;
                const maxIncidentRate = filterData.get('maxIncidentRate') || 5;
                const minSafety = filterData.get('minSafety') || 1;
                const maxSafety = filterData.get('maxSafety') || 5;
                return barangay.traffic >= minTraffic && barangay.traffic <= maxTraffic &&
                       barangay.incidentRate >= minIncidentRate && barangay.incidentRate <= maxIncidentRate &&
                       barangay.safety >= minSafety && barangay.safety <= maxSafety;
            });
            updateMap(filteredData);
        });
    </script>
</body>
</html>
