<?php
session_start();
include '../layout/adminLayout.php';

if (!isset($_SESSION['id'])) {
    header('Location: https://logistic2.moverstaxi.com/pages/login.php');
    exit();
}

$dashboardContent = '
<main class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Transport Asset Management</h1>
    <nav class="text-sm text-gray-500 mb-6">
        <a href="admindashboard.php" class="hover:underline">Home</a>
        <span class="mx-2">/</span>
        <a href="transport_assets.php" class="font-semibold text-blue-600">Transport Assets</a>
    </nav>

    <section class="bg-white p-6 rounded shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <button id="addTransportAssetBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Transport Asset</button>
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
                        <th class="px-4 py-2 border">Vehicle ID</th>
                        <th class="px-4 py-2 border">Plate Number</th>
                        <th class="px-4 py-2 border">Brand</th>
                        <th class="px-4 py-2 border">Model</th>
                        <th class="px-4 py-2 border">Year</th>
                        <th class="px-4 py-2 border">Depreciation Value</th>
                        <th class="px-4 py-2 border">VIN</th>
                        <th class="px-4 py-2 border">Condition</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                   
                </tbody>
            </table>
        </div>
    </section>

    <!-- Transport Asset Modal -->
   <div id="transportAssetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 relative">
        <button onclick="document.getElementById(\'transportAssetModal\').classList.add(\'hidden\')" class="absolute top-4 right-4 text-gray-600 hover:text-red-500">
            ✕
        </button>
        <h2 class="text-2xl font-semibold mb-4">Add New Transport Asset</h2>
        <form id="transportAssetForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Vehicle ID</label>
                <input type="text" name="vehicleId" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                <input type="text" name="plateNumber" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Brand</label>
                <input type="text" name="brand" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Model</label>
                <input type="text" name="model" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Year</label>
                <input type="number" name="year" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Depreciation Value</label>
                <input type="number" name="depreciationValue" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">VIN</label>
                <input type="text" name="vin" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Condition</label>
                <select name="condition" class="mt-1 w-full border rounded-md p-2" required>
                    <option value="" disabled selected>Select Condition</option>
                    <option>New</option>
                    <option>Used</option>
                    <option>Damaged</option>
                </select>
            </div>
            <div class="md:col-span-2 flex justify-around mt-4">
                <button type="button" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700" onclick="document.getElementById(\'transportAssetModal\').classList.add(\'hidden\')">Cancel</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Submit</button>
            </div>
        </form>
    </div>
</div>


<!-- Update Transport Asset Modal -->
<div id="updateTransportAssetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 relative">
        <button onclick="document.getElementById(\'updateTransportAssetModal\').classList.add(\'hidden\')" class="absolute top-4 right-4 text-gray-600 hover:text-red-500">
            ✕
        </button>
        <h2 class="text-2xl font-semibold mb-4">Update Transport Asset</h2>
        <form id="updateTransportAssetForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Vehicle ID</label>
                <input type="text" id="updateVehicleId" name="vehicleId" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Plate Number</label>
                <input type="text" id="updatePlateNumber" name="plateNumber" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Brand</label>
                <input type="text" id="updateBrand" name="brand" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Model</label>
                <input type="text" id="updateModel" name="model" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Year</label>
                <input type="number" id="updateYear" name="year" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Depreciation Value</label>
                <input type="number" id="updateDepreciationValue" name="depreciationValue" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">VIN</label>
                <input type="text" id="updateVin" name="vin" class="mt-1 w-full border rounded-md p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Condition</label>
                <select id="updateCondition" name="condition" class="mt-1 w-full border rounded-md p-2" required>
                    <option value="" disabled>Select Condition</option>
                    <option>New</option>
                    <option>Used</option>
                    <option>Damaged</option>
                </select>
            </div>
            <div class="md:col-span-2 flex justify-around mt-4">
                <button type="button" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700" onclick="document.getElementById(\'updateTransportAssetModal\').classList.add(\'hidden\')">Cancel</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Update</button>
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
        document.getElementById("transportAssetModal").classList.remove("hidden");
    });

   
document.getElementById("transportAssetForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(this);
    const data = {
    vehicle_id: formData.get("vehicleId"),  // change 'vehicleId' to 'vehicle_id'
    plate_number: formData.get("plateNumber"),  // change 'plateNumber' to 'plate_number'
    brand: formData.get("brand"),
    model: formData.get("model"),
    year: formData.get("year"),
    depreciation_value: formData.get("depreciationValue"),  // change 'depreciationValue' to 'depreciation_value'
    vin: formData.get("vin"),
    condition: formData.get("condition")
};


    // Send data using Fetch API
    fetch('https://logistic2.moverstaxi.com/api/transport_assets.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json()) // parse JSON response
    .then(data => {
        // Handle the response from the server
        alert(data.message);  // Assuming your API responds with a message
        document.getElementById("transportAssetModal").classList.add("hidden"); // Close modal
        this.reset();
        location.reload();

        
    })
    .catch(error => {
        // Handle errors
        console.error('Error:', error);
        alert('Error submitting form!');
    });
});


document.addEventListener('DOMContentLoaded', () => {
    // Fetch transport assets from the API
    fetch('https://logistic2.moverstaxi.com/api/transport_assets.php')
        .then(response => response.json()) // parse JSON response
        .then(data => {
            // Get the table body element
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = ''; // Clear existing table rows

            // Loop through the data and add rows to the table
            data.forEach(asset => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-gray-50');

                // Create table cells for each asset field
                row.innerHTML = `
                    <td class="px-4 py-2 border">${asset.vehicle_id}</td>
                    <td class="px-4 py-2 border">${asset.plate_number}</td>
                    <td class="px-4 py-2 border">${asset.brand}</td>
                    <td class="px-4 py-2 border">${asset.model}</td>
                    <td class="px-4 py-2 border">${asset.year}</td>
                    <td class="px-4 py-2 border">${asset.depreciation_value}</td>
                    <td class="px-4 py-2 border">${asset.vin}</td>
                    <td class="px-4 py-2 border">${asset.condition}</td>
                    <td class="px-4 py-2 border">
                        <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700" onclick="openUpdateModal(${asset.id})">Edit</button>
                        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700" onclick="deleteTransportAsset(${asset.id})">Delete</button>

                    </td>
                `;
                
                // Append the new row to the table
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching transport assets:', error);
            
        });
});


function deleteTransportAsset(id) {
    // Confirm before deleting
    if (confirm('Are you sure you want to delete this asset?')) {
        // Send a DELETE request using fetch
        fetch(`https://logistic2.moverstaxi.com/api/transport_assets.php?id=${id}`, {
            method: 'DELETE', // HTTP method
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            // Handle the response data
            if (data.message) {
                alert(data.message); // Show success message
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
          
        });
    }
}


// Function to open the update modal and pre-fill the form with asset data
function openUpdateModal(id) {
    fetch(`https://logistic2.moverstaxi.com/api/transport_assets.php?id=${id}`)
        .then(response => response.json())
        .then(asset => {
            // Populate the modal with asset data
            document.getElementById('updateVehicleId').value = asset.vehicle_id;
            document.getElementById('updatePlateNumber').value = asset.plate_number;
            document.getElementById('updateBrand').value = asset.brand;
            document.getElementById('updateModel').value = asset.model;
            document.getElementById('updateYear').value = asset.year;
            document.getElementById('updateDepreciationValue').value = asset.depreciation_value;
            document.getElementById('updateVin').value = asset.vin;
            document.getElementById('updateCondition').value = asset.condition;

            // Show the update modal
            document.getElementById('updateTransportAssetModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error fetching asset details:', error));
}

// Handle the form submission for updating the asset
document.getElementById("updateTransportAssetForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        id: formData.get("id"),  // Include the ID for updating
        vehicle_id: formData.get("vehicleId"),
        plate_number: formData.get("plateNumber"),
        brand: formData.get("brand"),
        model: formData.get("model"),
        year: formData.get("year"),
        depreciation_value: formData.get("depreciationValue"),
        vin: formData.get("vin"),
        condition: formData.get("condition")
    };

    // Send PUT request to update the asset
    fetch(`https://logistic2.moverstaxi.com/api/transport_assets.php`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);  // Show success message
        document.getElementById('updateTransportAssetModal').classList.add('hidden'); // Close modal
       
    })
    .catch(error => {
        console.error('Error updating asset:', error);
        alert('Error updating asset!');
    });
});



</script>
