<?php
session_start();
include '../layout/adminLayout.php';



$dashboardContent = ' 
<div class="bg-gray-100 flex items-start justify-center h-screen flex-col pl-[1rem] ">
    <h1 class="text-2xl font-bold text-gray-800">Tracking</h1>  
    <ul class="flex space-x-2 text-gray-600 mt-4">
        <li><a href="admindashboard.php" class="text-gray-800">Home</a></li>
        <li>/</li>
        <li><a href="#" class="text-blue-600 font-semibold">Order Details</a></li>
    </ul>

    <div class="flex justify-center items-center flex-col w-full">
        <div class="mb-4 flex items-center space-x-2 mt-4">
            <input type="text" id="trackingNumber" placeholder="Enter Tracking Number (e.g., TRACK123)" class="border p-2 rounded">
            <button onclick="getRouteData()" class="bg-blue-500 text-white px-4 py-2 rounded">Track</button>
        </div>

        <div id="map" class="rounded-lg shadow-md w-3/4 h-96"></div>

        <div class="mt-6 w-3/4 mb-10">
            <table class="min-w-full bg-white border border-gray-300 shadow-md">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="py-2 px-4 border">Tracking Number</th>
                        <th class="py-2 px-4 border">Start Location</th>
                        <th class="py-2 px-4 border">End Location</th>
                        <th class="py-2 px-4 border">Status</th>
                    </tr>
                </thead>
                <tbody id="tracking-table-body">
                    <tr class="text-center">
                        <td colspan="4" class="py-2 px-4 border text-gray-500">Loading data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>';

adminLayout($dashboardContent);
?>

<script>
    var map = L.map('map').setView([14.6042, 120.9822], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let startMarker, endMarker, routeLayer;

    async function fetchAllTrackingData() {
        try {
            const response = await fetch('https://logistic2.moverstaxi.com/api/routeApi.php');
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            const data = await response.json();

            let tableBody = document.getElementById("tracking-table-body");
            tableBody.innerHTML = "";

            if (data.length === 0) {
                tableBody.innerHTML = `<tr class="text-center"><td colspan="4" class="py-2 px-4 border text-gray-500">No tracking data available</td></tr>`;
                return;
            }

            data.forEach(item => {
                tableBody.innerHTML += `
                    <tr class="text-center">
                        <td class="py-2 px-4 border">${item.tracking_number}</td>
                        <td class="py-2 px-4 border">${item.pickup_location}</td>
                        <td class="py-2 px-4 border">${item.dropoff_location}</td>
                        <td class="py-2 px-4 border">In Transit</td>
                    </tr>`;
            });
        } catch (error) {
            console.error("Fetch error:", error);
        }
    }

    async function getRouteData() {
        let trackingNumber = document.getElementById("trackingNumber").value.trim();
        if (!trackingNumber) {
            alert("Please enter a tracking number.");
            return;
        }

        try {
            const response = await fetch(`https://logistic2.moverstaxi.com/api/routeApi.php?trackingNumber=${trackingNumber}`);
            if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);
            const data = await response.json();
            
            if (!data.route) {
                alert(data.error || "No tracking data found.");
                return;
            }

            let pickup = data.route.pickup;
            let dropoff = data.route.dropoff;

            // Remove existing markers and route
            if (startMarker) map.removeLayer(startMarker);
            if (endMarker) map.removeLayer(endMarker);
            if (routeLayer) map.removeLayer(routeLayer);

            let pickupCoords = [parseFloat(pickup.latitude), parseFloat(pickup.longitude)];
            let dropoffCoords = [parseFloat(dropoff.latitude), parseFloat(dropoff.longitude)];

            startMarker = L.marker(pickupCoords).addTo(map).bindPopup("<b>Pickup: " + pickup.location + "</b>").openPopup();
            endMarker = L.marker(dropoffCoords).addTo(map).bindPopup("<b>Dropoff: " + dropoff.location + "</b>").openPopup();

            routeLayer = L.polyline([pickupCoords, dropoffCoords], { color: 'blue', weight: 5 }).addTo(map);
            map.fitBounds([pickupCoords, dropoffCoords]);
        } catch (error) {
            console.error("Fetch error:", error);
            alert("Failed to fetch tracking data. Please try again later.");
        }
    }

    window.onload = fetchAllTrackingData;
</script>
