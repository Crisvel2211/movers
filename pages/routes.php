<?php
session_start();
include '../layout/adminLayout.php';

if (!isset($_SESSION['id'])) {
    header('Location: https://logistic2.moverstaxi.com/pages/login.php');
    exit();
}

$dashboardContent = '
<main class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Routes Tracking</h1>
    <nav class="text-sm text-gray-500 mb-6">
        <a href="admindashboard.php" class="hover:underline">Home</a>
        <span class="mx-2">/</span>
        <a href="transport_assets.php" class="font-semibold text-blue-600">Routes</a>
    </nav>

    <section class="bg-white p-6 rounded shadow-md">
       <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <button id="addTransportAssetBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Routes</button>
            <input type="text" id="searchTransportInput" placeholder="Search Transport Assets..." class="border px-3 py-2 rounded w-full md:w-auto" />
            <select id="filterTransportStatus" class="border px-3 py-2 rounded">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>

        <div class="overflow-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border-b px-4 py-2 text-left font-medium">Pickup Location</th>
                        <th class="border-b px-4 py-2 text-left font-medium">Dropoff Location</th>
                        <th class="border-b px-4 py-2 text-left font-medium">Start Lat</th>
                        <th class="border-b px-4 py-2 text-left font-medium">Start Lng</th>
                        <th class="border-b px-4 py-2 text-left font-medium">End Lat</th>
                        <th class="border-b px-4 py-2 text-left font-medium">End Lng</th>
                    </tr>
                </thead>
                <tbody id="routesTableBody">
                    <!-- Dynamic rows will be added here -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Transport Asset Modal -->
    <div id="routeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 relative">
            <button onclick="document.getElementById(\'routeModal\').classList.add(\'hidden\')" class="absolute top-4 right-4 text-gray-600 hover:text-red-500">
                ✕
            </button>
            <h2 class="text-2xl font-semibold mb-4">Add New Route</h2>
            <form id="routeForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pickup Location</label>
                    <input type="text" name="pickup_location" class="mt-1 w-full border rounded-md p-2" required placeholder="Enter pickup location">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dropoff Location</label>
                    <input type="text" name="dropoff_location" class="mt-1 w-full border rounded-md p-2" required placeholder="Enter dropoff location">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Latitude</label>
                    <input type="number" step="0.000001" name="start_lat" class="mt-1 w-full border rounded-md p-2" required placeholder="Enter start latitude">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Longitude</label>
                    <input type="number" step="0.000001" name="start_lng" class="mt-1 w-full border rounded-md p-2" required placeholder="Enter start longitude">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Latitude</label>
                    <input type="number" step="0.000001" name="end_lat" class="mt-1 w-full border rounded-md p-2" required placeholder="Enter end latitude">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Longitude</label>
                    <input type="number" step="0.000001" name="end_lng" class="mt-1 w-full border rounded-md p-2" required placeholder="Enter end longitude">
                </div>
                <div class="md:col-span-2 flex justify-around mt-4">
                    <button type="button" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700" onclick="document.getElementById(\'routeModal\').classList.add(\'hidden\')">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Submit</button>
                </div>
            </form>
        </div>
    </div>

</main>
';

adminLayout($dashboardContent);
?>

<script>
    document.getElementById("addTransportAssetBtn").addEventListener("click", () => {
        document.getElementById("routeModal").classList.remove("hidden");
    });

    // Fetch and display existing routes when the page loads
    window.onload = function() {
        fetchRoutes();
    };

    function fetchRoutes() {
    fetch('https://logistic2.moverstaxi.com/api/routeApi.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data) && data.length > 0) {
            // Iterate over each route in the response data
            data.forEach(route => {
                const row = document.createElement('tr');
                row.classList.add('border-b');

                // Accessing nested values for pickup and dropoff latitudes and longitudes
                const startLat = route.pickup.latitude;
                const startLng = route.pickup.longitude;
                const endLat = route.dropoff.latitude;
                const endLng = route.dropoff.longitude;

                row.innerHTML = `
                    <td class="px-4 py-2">${route.pickup_location}</td>
                    <td class="px-4 py-2">${route.dropoff_location}</td>
                    <td class="px-4 py-2">${startLat}</td>
                    <td class="px-4 py-2">${startLng}</td>
                    <td class="px-4 py-2">${endLat}</td>
                    <td class="px-4 py-2">${endLng}</td>
                `;

                // Append the row to the table body
                document.getElementById('routesTableBody').appendChild(row);
            });
        } else {
            alert('No routes found');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching the routes.');
    });
}


    document.getElementById('routeForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Get the form values
        const pickupLocation = event.target.pickup_location.value;
        const dropoffLocation = event.target.dropoff_location.value;
        const startLat = event.target.start_lat.value;
        const startLng = event.target.start_lng.value;
        const endLat = event.target.end_lat.value;
        const endLng = event.target.end_lng.value;

        // Create a new route object
        const routeData = {
            pickup_location: pickupLocation,
            dropoff_location: dropoffLocation,
            start_lat: startLat,
            start_lng: startLng,
            end_lat: endLat,
            end_lng: endLng
        };

        // Send the POST request to the server
        fetch('https://logistic2.moverstaxi.com/api/routeApi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(routeData)
        })
        .then(response => response.json())
        .then(data => {
            // Check if the route was added successfully
            if (data.success) {
                // Create a new row for the table
                const row = document.createElement('tr');
                row.classList.add('border-b');

                row.innerHTML = `
                    <td class="px-4 py-2">${pickupLocation}</td>
                    <td class="px-4 py-2">${dropoffLocation}</td>
                    <td class="px-4 py-2">${startLat}</td>
                    <td class="px-4 py-2">${startLng}</td>
                    <td class="px-4 py-2">${endLat}</td>
                    <td class="px-4 py-2">${endLng}</td>
                `;
                
                // Append the row to the table body
                document.getElementById('routesTableBody').appendChild(row);

                // Close the modal
                document.getElementById('routeModal').classList.add('hidden');
            } else {
                alert('Failed to add the route');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the route.');
        });
    });
</script>
