<?php
session_start();
include '../layout/adminLayout.php';



// HTML form with TailwindCSS & Toastify.js
$dashboardContent = '
    <div class="max-w-lg mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Add New Route</h2>
        <form id="routeForm" class="space-y-4">
            <div>
                <label for="pickup" class="block text-sm font-medium text-gray-700">Pickup Location</label>
                <input type="text" id="pickup" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="dropoff" class="block text-sm font-medium text-gray-700">Dropoff Location</label>
                <input type="text" id="dropoff" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="start_lat" class="block text-sm font-medium text-gray-700">Pickup Latitude</label>
                <input type="number" step="any" id="start_lat" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="start_lng" class="block text-sm font-medium text-gray-700">Pickup Longitude</label>
                <input type="number" step="any" id="start_lng" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="end_lat" class="block text-sm font-medium text-gray-700">Dropoff Latitude</label>
                <input type="number" step="any" id="end_lat" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div>
                <label for="end_lng" class="block text-sm font-medium text-gray-700">Dropoff Longitude</label>
                <input type="number" step="any" id="end_lng" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>

            <div class="mb-4">
                <button id="login-btn" type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex justify-center items-center">
                    <span id="login-btn-text">Submit</span>
                    <svg id="login-spinner" class="hidden ml-2 w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.964 7.964 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Include Toastify.js -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script>
        function showToast(message, type) {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: type === "success" 
                    ? "linear-gradient(to right, #00b09b, #96c93d)" 
                    : "linear-gradient(to right, #ff5f6d, #ffc371)",
                stopOnFocus: true,
            }).showToast();
        }

        document.getElementById("routeForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let loginBtn = document.getElementById("login-btn");
            let loginBtnText = document.getElementById("login-btn-text");
            let loginSpinner = document.getElementById("login-spinner");
            
            loginBtnText.textContent = "Submitting...";
            loginSpinner.classList.remove("hidden");

            const formData = {
                pickup_location: document.getElementById("pickup").value,
                dropoff_location: document.getElementById("dropoff").value,
                start_lat: parseFloat(document.getElementById("start_lat").value),
                start_lng: parseFloat(document.getElementById("start_lng").value),
                end_lat: parseFloat(document.getElementById("end_lat").value),
                end_lng: parseFloat(document.getElementById("end_lng").value)
            };

            if (!formData.pickup_location || !formData.dropoff_location) {
                showToast("Pickup and Dropoff locations are required!", "error");
                return;
            }

            setTimeout(async () => {
                try {
                    let response = await fetch(https://logistic2.moverstaxi.com/api/routeApi.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(formData)
                    });
                    let data = await response.json();
                    
                    loginBtnText.textContent = "Submit";
                    loginSpinner.classList.add("hidden");

                    if (data.success) {
                        showToast(data.success, "success");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showToast(data.error, "error");
                    }
                } catch (error) {
                    loginBtnText.textContent = "Submit";
                    loginSpinner.classList.add("hidden");
                    showToast("An error occurred. Please try again.", "error");
                }
            }, 2000);
        });
    </script>
';

adminLayout($dashboardContent);
?>
