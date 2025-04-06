<?php
session_start();
include '../layout/adminLayout.php';

$dashboardContent = '
<main class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800">Order Details</h1>
    <ul class="flex space-x-2 text-gray-600 mt-4">
        <li><a href="admindashboard.php" class="text-gray-800">Home</a></li>
        <li>/</li>
        <li><a href="#" class="text-blue-600 font-semibold">Order Details</a></li>
    </ul>

    <div class="mt-6 bg-white shadow-md p-4 rounded-lg">
        <h3 class="text-lg font-semibold">Order Details</h3>
        <table class="w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Order ID</th>
                    <th class="border p-2">Order Type</th>
                    <th class="border p-2">Pickup Location</th>
                    <th class="border p-2">Dropoff Location</th>
                    <th class="border p-2">Shipping Date & Time</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Action</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                <!-- Order details dynamically inserted here -->
            </tbody>
        </table>
    </div>
</main>

';

adminLayout($dashboardContent);
?>

<script>
    // Fetch all orders and populate the table
    async function getOrders() {
        try {
            const response = await fetch('https://logistic2.moverstaxi.com/api/orders.php');
            const orders = await response.json();

            const orderTableBody = document.getElementById('orderTableBody');
            orderTableBody.innerHTML = ''; // Clear any existing rows

            orders.forEach(order => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-gray-100'); // Add hover effect

                row.innerHTML = `
                    <td class="border p-2">${order.order_id}</td>
                    <td class="border p-2">${order.order_type}</td>
                    <td class="border p-2">${order.pickup_location}</td>
                    <td class="border p-2">${order.dropoff_location}</td>
                    <td class="border p-2">${order.shipping_date_time}</td>
                    <td class="border p-2">${order.status}</td>
                    <td class="border p-2">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="acceptOrder(${order.order_id})">Accept</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded-md" onclick="declineOrder(${order.order_id})">Decline</button>
                    </td>
                `;
                
                orderTableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error fetching orders:', error);
        }
    }

    // Accept order function (sending request to acceptOrder.php)
    async function acceptOrder(orderId) {
        try {
            const response = await fetch('https://logistic2.moverstaxi.com/api/acceptOrder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId, status: 'Accepted' })
            });

            const result = await response.json();

            if (result.success) {
                alert('Order accepted successfully');
                getOrders(); // Refresh the order list
            } else {
                alert('Failed to accept the order');
            }
        } catch (error) {
            console.error('Error accepting order:', error);
            alert('Error occurred while accepting the order');
        }
    }

    // Decline order function (sending request to declineOrder.php)
    async function declineOrder(orderId) {
        try {
            const response = await fetch('https://logistic2.moverstaxi.com/api/declineOrder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId, status: 'Declined' })
            });

            const result = await response.json();

            if (result.success) {
                alert('Order declined successfully');
                getOrders(); // Refresh the order list
            } else {
                alert('Failed to decline the order');
            }
        } catch (error) {
            console.error('Error declining order:', error);
            alert('Error occurred while declining the order');
        }
    }

    // Load orders when the page loads
    window.onload = getOrders;
</script>

