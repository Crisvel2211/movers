<?php
session_start();
include '../layout/adminLayout.php';

if (!isset($_SESSION['id'])) {
    header('Location: http://localhost/movers/pages/login.php');
    exit();
}

$dashboardContent = '
<main class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Asset Management</h1>
    <nav class="text-sm text-gray-500 mb-6">
        <a href="admindashboard.php" class="hover:underline">Home</a>
        <span class="mx-2">/</span>
        <a href="asset.php" class="font-semibold text-blue-600">Consumable Asset</a>
    </nav>

    <section class="bg-white p-6 rounded shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <button id="addAssetBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Asset</button>
            <input type="text" id="searchInput" placeholder="Search Assets..." class="border px-3 py-2 rounded w-full md:w-auto" />
            <select id="filterStatus" class="border px-3 py-2 rounded">
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
                        <th class="px-4 py-2 border">Asset Type</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">Examples</th>
                        <th class="px-4 py-2 border">Lifespan</th>
                        <th class="px-4 py-2 border">Replenishment Frequency</th>
                        <th class="px-4 py-2 border">Estimated Cost</th>
                        <th class="px-4 py-2 border">Usage Impact</th>
                        <th class="px-4 py-2 border">Stock Capacity</th>
                        <th class="px-4 py-2 border">Current Stock</th>
                        <th class="px-4 py-2 border">Reorder Point</th>
                        <th class="px-4 py-2 border">Storage Requirements</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">Engine Oil</td>
                        <td class="px-4 py-2 border">Lubricates engine parts to reduce wear</td>
                        <td class="px-4 py-2 border">Shell Helix, Caltex Delo</td>
                        <td class="px-4 py-2 border">6 months</td>
                        <td class="px-4 py-2 border">Monthly</td>
                        <td class="px-4 py-2 border">₱2,500 per drum</td>
                        <td class="px-4 py-2 border">High impact on vehicle health</td>
                        <td class="px-4 py-2 border">50 drums</td>
                        <td class="px-4 py-2 border">20 drums</td>
                        <td class="px-4 py-2 border">15 drums</td>
                        <td class="px-4 py-2 border">Cool, dry area</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Asset Modal -->
    <div id="assetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6 relative">
            <button onclick="document.getElementById(\'assetModal\').classList.add(\'hidden\')" class="absolute top-4 right-4 text-gray-600 hover:text-red-500">
                ✕
            </button>
            <h2 class="text-2xl font-semibold mb-4">Add New Asset</h2>
            <form id="assetForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Asset Type</label>
                     <select name="assetType" class="mt-1 w-full border rounded-md p-2" required>
                        <option value="" disabled selected>Select Asset Type</option>
                        <option>Fuel & Energy Supplies</option>
                        <option>Vehicle Maintenance Supplies</option>
                        <option>Packaging Materials</option>
                        <option>Office Materials</option>
                        <option>Printer Ink & Paper</option>
                        <option>Cleaning Supplies</option>
                        <option>Personal Protective Equipment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="description" class="mt-1 w-full border rounded-md p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Examples</label>
                    <input type="text" name="examples" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lifespan</label>
                    <input type="text" name="lifespan" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Replenishment Frequency</label>
                    <input type="text" name="frequency" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estimated Cost</label>
                    <input type="text" name="cost" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Usage Impact</label>
                    <input type="text" name="impact" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock Capacity</label>
                    <input type="number" name="capacity" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                    <input type="number" name="current" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reorder Point</label>
                    <input type="number" name="reorder" class="mt-1 w-full border rounded-md p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Storage Requirements</label>
                    <select name="storage" class="mt-1 w-full border rounded-md p-2">
                        <option value="" disabled selected>Select Storage Requirement</option>
                        <option>Fuel & Energy Supplies – Well ventilated, fire-safe areas</option>
                        <option>Vehicle Maintenance Supplies – Store in dry, organize in cabinets</option>
                        <option>Packaging Materials – Keep in clean, dry spaces</option>
                        <option>Office Materials – Store in cool, dry cabinets</option>
                        <option>Personal Protective Equipment (PPE) – Keep clean, dry, and accessible</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-around mt-4">
                    <button class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700" onclick="document.getElementById(\'assetModal\').classList.add(\'hidden\')">Cancel</button>
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
        document.getElementById("addAssetBtn").addEventListener("click", () => {
            document.getElementById("assetModal").classList.remove("hidden");
        });

        document.getElementById("assetForm").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Asset submitted! (You can now add backend PHP to process this form.)");
            document.getElementById("assetModal").classList.add("hidden");
            this.reset();
        });
    </script>