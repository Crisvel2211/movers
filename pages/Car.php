<?php
session_start();
include '../layout/adminLayout.php';



$dashboardContent = '
<main class="p-6">
    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-gray-800">Shipping</h1>
    
    <!-- Breadcrumbs -->
    <nav class="mt-4">
        <ul class="flex text-gray-600 space-x-2">
            <li><a href="admindashboard.php" class="text-gray-500">Home</a></li>
            <li>/</li>
            <li class="text-blue-600 font-semibold">Routes Selection</li>
        </ul>
    </nav>

    <!-- Filter Options -->
    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Filter Routes/Carriers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="route-type" class="block font-medium">Route Type:</label>
                <select id="route-type" class="w-full p-2 border rounded">
                    <option value="all">All</option>
                    <option value="shortest">Shortest</option>
                    <option value="cheapest">Cheapest</option>
                    <option value="fastest">Fastest</option>
                </select>
            </div>
            <div>
                <label for="carrier-type" class="block font-medium">Carrier:</label>
                <select id="carrier-type" class="w-full p-2 border rounded">
                    <option value="all">All</option>
                    <option value="taxi">Taxi</option>
                    <option value="van">Van</option>
                    <option value="bus">Bus</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Available Routes -->
    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Available Routes</h2>
        <div id="routes-list" class="text-gray-700">
            <!-- Routes will be dynamically inserted here -->
        </div>
    </div>

    <!-- Selected Routes Table -->
    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Selected Routes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-3 text-left">Route Name</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Carrier</th>
                        <th class="p-3 text-left">Cost</th>
                        <th class="p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody id="selected-routes-list" class="divide-y divide-gray-300">
                    <!-- Selected routes will appear here -->
                </tbody>
            </table>
        </div>
    </div>
</main>';

adminLayout($dashboardContent);
?>