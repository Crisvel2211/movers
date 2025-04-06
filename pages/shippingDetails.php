<?php
session_start();
include '../layout/adminLayout.php';

$dashboardContent = '
<main class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800">Shipping Details</h1>
    <ul class="flex space-x-2 text-gray-600 mt-4">
        <li><a href="admindashboard.php" class="text-gray-800">Home</a></li>
        <li>/</li>
        <li><a href="#" class="text-blue-600 font-semibold">Shipping Details</a></li>
    </ul>

    <div class="mt-6 bg-white shadow-md p-4 rounded-lg">
        <h3 class="text-lg font-semibold">Shipping Details</h3>
        <table class="w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Order ID</th>
                    <th class="border p-2">Tracking Number</th>
                    <th class="border p-2">Shipment Date</th>
                    <th class="border p-2">Destination</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="shippingTableBody">
                <!-- Shipping details dynamically inserted here -->
            </tbody>
        </table>
    </div>

   <!-- Modal for Updating Shipment -->
<div id="updateModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h3 class="text-xl font-semibold mb-4">Update Shipment Details</h3>
        <form id="updateForm">
            <label for="shipment_date" class="block mb-2">Shipment Date</label>
            <input type="date" id="shipment_date" name="shipment_date" class="border p-2 w-full mb-4" required>

            <label for="destination" class="block mb-2">Destination</label>
            <input type="text" id="destination" name="destination" class="border p-2 w-full mb-4" required>

            <label for="status" class="block mb-2">Status</label>
            <select id="status" name="status" class="border p-2 w-full mb-4" required>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>

            <label for="tracking_number" class="block mb-2">Tracking Number</label>
            <input type="text" id="tracking_number" name="tracking_number" class="border p-2 w-full mb-4" required>

            <input type="hidden" id="shipment_id" name="shipment_id">
            
            <div class="flex justify-around">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Update</button>
                <button type="button" onclick="closeModal()" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-md">Cancel</button>
            </div>
        </form>
    </div>
</div>


</main>
';

adminLayout($dashboardContent);
?>

<script>
    let selectedShipmentId = null;

    // Fetch all shipping details and populate the table
    async function getShipping() {
        try {
            const response = await fetch('https://logistic2.moverstaxi.com/api/shipments.php');
            const shippingDetails = await response.json();

            const shippingTableBody = document.getElementById('shippingTableBody');
            shippingTableBody.innerHTML = ''; // Clear any existing rows

            shippingDetails.forEach(shipment => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-gray-100'); // Add hover effect

                row.innerHTML = `
                    <td class="border p-2">${shipment.shipment_id}</td>
                    <td class="border p-2">${shipment.tracking_number}</td>
                    <td class="border p-2">${shipment.shipment_date}</td>
                    <td class="border p-2">${shipment.destination}</td>
                    <td class="border p-2">${shipment.shipment_status}</td>
                    <td class="border p-2">
                        <button onclick="updateShipment(${shipment.shipment_id}, '${shipment.shipment_date}', '${shipment.destination}', '${shipment.shipment_status}', '${shipment.tracking_number}')" class="text-blue-600 hover:underline">Update</button>
                        <button onclick="deleteShipment(${shipment.shipment_id})" class="text-red-600 hover:underline">Delete</button>
                    </td>
                `;
                
                shippingTableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error fetching shipping details:', error);
        }
    }

    function updateShipment(shipmentId, shipmentDate, destination, status, trackingNumber) {
    // Store the shipment ID for later use
    selectedShipmentId = shipmentId; 
    
    // Format the date to ensure it's in the correct 'YYYY-MM-DD' format (if necessary)
    function formatDate(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed, so add 1
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Set the input values in the modal
    document.getElementById('shipment_date').value = formatDate(shipmentDate);
    document.getElementById('destination').value = destination;
    document.getElementById('status').value = status;
    document.getElementById('tracking_number').value = trackingNumber;
    document.getElementById('shipment_id').value = shipmentId;

    // Show the modal
    document.getElementById('updateModal').classList.remove('hidden');
}



    // Close the modal
    function closeModal() {
        document.getElementById('updateModal').classList.add('hidden');
    }

    // Handle form submission for updating shipment
    document.getElementById('updateForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const shipmentData = {
            shipment_id: selectedShipmentId,
            shipment_date: document.getElementById('shipment_date').value,
            destination: document.getElementById('destination').value,
            shipment_status: document.getElementById('status').value,
            tracking_number: document.getElementById('tracking_number').value // Add tracking number here
        };

        try {
            const response = await fetch(`https://logistic2.moverstaxi.com/api/shipments.php?id=${selectedShipmentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(shipmentData),
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                getShipping(); // Refresh the table after update
                closeModal(); // Close the modal
            } else {
                alert('Failed to update shipment');
            }
        } catch (error) {
            console.error('Error updating shipment:', error);
        }
    });

    // Delete shipment
    async function deleteShipment(shipmentId) {
        const confirmation = confirm("Are you sure you want to delete this shipment?");
        if (confirmation) {
            try {
                const response = await fetch(`https://logistic2.moverstaxi.com/api/shipments.php?id=${shipmentId}`, {
                    method: 'DELETE',
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    getShipping(); // Refresh the table after deletion
                } else {
                    alert('Failed to delete shipment');
                }
            } catch (error) {
                console.error('Error deleting shipment:', error);
            }
        }
    }

    // Load shipping details when the page loads
    window.onload = getShipping;
</script>
