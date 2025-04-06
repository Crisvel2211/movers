<?php
session_start();
include '../layout/adminLayout.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: https://logistic2.moverstaxi.com'); // Redirect to login page if not logged in
    exit();
}


$dashboardContent = '
<main class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800">Welcome '.htmlspecialchars($_SESSION['username']).'!</h1>
    <ul class="flex space-x-2 text-gray-600 mt-4">     
    </ul>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold">Total Shipments</h2>
            <p class="text-2xl font-bold">1540</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold">Total Vehicles</h2>
            <p class="text-2xl font-bold">758</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold">Total Maintenance</h2>
            <p class="text-2xl font-bold">209</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold">Total Deliveries</h2>
            <p class="text-2xl font-bold">5,380</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold">Shipments Data</h3>
            <div id="chart"></div>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold">Fleet Maintenance Data</h3>
            <div id="chart1"></div>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold">Delivery Fleet Performance</h3>
            <div id="chart2"></div>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-xl font-semibold">Data Analysis</h3>
            <div id="chart3"></div>
        </div>
    </div>

    <footer class="mt-6 text-center text-gray-600">&copy; 2024 Analytics Dashboard. All Rights Reserved.</footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="script.js"></script>
';

adminLayout($dashboardContent);
?>
