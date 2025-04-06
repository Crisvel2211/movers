<?php
function adminLayout($children) {
    $currentPage = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']); 
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Analysis</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css" rel="stylesheet">
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    </head>
    <body class="bg-gray-100">

        <!-- SIDEBAR -->
        <section id="sidebar" class="fixed left-0 top-0 w-64 h-full bg-white shadow-lg transition-transform transform">
            <div class="p-4 flex justify-center">
                <a href="admindashboard.php"><img src="../images/mover.jpg" alt="Logo" class="w-32"></a>
            </div>
            <ul class="text-gray-700">

                <li>
                    <a href="admindashboard.php" class="flex items-center px-4 py-3 transition 
                    <?= ($currentPage == 'admindashboard.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100 text-blue-600'; ?>">
                        <i class='bx bxs-dashboard mr-2'></i> Dashboard
                    </a>
                </li>

                <li class="px-4 py-2 text-gray-500 uppercase text-sm font-semibold">Main</li>

                <!-- Shipping -->
                <li>
                    <a href="#" class="dropdown-toggle flex justify-between items-center px-4 py-3 hover:bg-blue-200 transition">
                        <div class="flex items-center gap-2"> 
                            <i class='bx bxs-package text-blue-600'></i>
                            <span class="text-gray-700">Shipping</span>
                        </div>
                        <i class='bx bx-chevron-right chevron-icon transition-transform'></i>
                    </a>
                    <ul class="ml-4 hidden transition-all">
                        <li><a href="orderDetails.php" class="block px-4 py-2 transition
                        <?= ($currentPage == 'orderDetails.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Order Details</a></li>
                        <li><a href="shippingDetails.php" class="block px-4 py-2 transition
                        <?= ($currentPage == 'shippingDetails.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Shipping Details</a></li>
                        <li><a href="routes.php" class="block px-4 py-2 transition
                        <?= ($currentPage == 'routes.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Carriers Selection</a></li>
                    </ul>
                </li>

                <!-- Tracking -->
                <li>
                    <a href="#" class="dropdown-toggle flex justify-between items-center px-4 py-3 hover:bg-blue-200 transition">
                        <div class="flex items-center gap-2"> 
                            <i class='bx bx-current-location text-blue-600'></i>
                            <span class="text-gray-700">Tracking</span>
                        </div>
                        <i class='bx bx-chevron-right chevron-icon transition-transform'></i>
                    </a>
                    <ul class="ml-4 hidden transition-all">
                        <li><a href="routes.php" class="block px-4 py-2 transition
                        <?= ($currentPage == 'routes.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Routes</a></li>
                        <li><a href="tracking.php" class="block px-4 py-2 transition
                        <?= ($currentPage == 'tracking.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">GPS Tracking</a></li>
                    </ul>
                </li>

                <!-- Asset Management -->
                <li>
                    <a href="#" class="dropdown-toggle flex justify-between items-center px-4 py-3 hover:bg-blue-200 transition">
                        <div class="flex items-center gap-2"> 
                            <i class='bx bx-cog text-blue-600'></i>
                            <span class="text-gray-700">Asset Management</span>
                        </div>
                        <i class='bx bx-chevron-right chevron-icon transition-transform'></i>
                    </a>
                    <ul class="ml-4 hidden transition-all">
                            <li><a href="transport.php" class="block px-4 py-2 transition
                            <?= ($currentPage == 'transport.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Transport Assets</a></li>

                            <li><a href="consumable.php" class="block px-4 py-2 transition
                            <?= ($currentPage == 'consumable.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Supplies & Inventory</a></li>

                            <li><a href="land.php" class="block px-4 py-2 transition
                            <?= ($currentPage == 'land.php') ? 'bg-blue-300 text-blue-800' : 'hover:bg-blue-100'; ?>">Real Estate Assets</a></li>

                    </ul>
                </li>

            </ul>
        </section>


        <!-- END SIDEBAR -->

        <!-- CONTENT SECTION -->
        <section id="content" class="ml-64 p-4 transition-all">
            <!-- NAVBAR -->
            <nav class="flex justify-between items-center bg-white p-4 shadow-md">
    <button id="toggle-sidebar" class="text-gray-700 text-2xl"><i class="bx bx-menu"></i></button>

    <div class="flex items-center space-x-6">
        <!-- Notification Icon -->
        <div class="relative">
            <button id="notification-btn" class="text-gray-700 text-2xl"><i class="bx bx-bell"></i></button>
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">1</span>
            
            <!-- Notification Dropdown -->
            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-64">
                <ul class="text-gray-700">
                    <li><a href="bookdetails.html" class="block px-4 py-2">New trip request from Moris</a></li>
                    <li><a href="shipments.html" class="block px-4 py-2">Tracking update: Ride in progress</a></li>
                    <li><a href="#" class="block px-4 py-2">System update available</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Profile Dropdown -->
        <div class="relative">
            <button id="profile-btn" class="flex items-center">
                <img src="../images/marky.jpg" alt="Profile Picture" class="w-8 h-8 rounded-full">
            </button>
            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 bg-white shadow-lg rounded-lg w-48">
                <ul class="text-gray-700">
                    <li><a href="settings.html" class="block px-4 py-2"><i class="bx bxs-user"></i> Profile</a></li>
                    <li><a href="https://logistic2.moverstaxi.com" class="block px-4 py-2"><i class="bx bxs-log-out-circle"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
            <!-- END NAVBAR -->

            <!-- MAIN CONTENT -->
            <?php echo $children; ?>
        </section>
        <!-- END CONTENT SECTION -->

        <!-- External JavaScript -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Sidebar Toggle
                const toggleSidebar = document.getElementById("toggle-sidebar");
                const sidebar = document.getElementById("sidebar");
                const content = document.getElementById("content");

                toggleSidebar.addEventListener("click", function () {
                    sidebar.classList.toggle("-translate-x-full");
                    content.classList.toggle("ml-0");
                });

                // Notification Modal
                const notificationBtn = document.getElementById("notification-btn");
                const notificationDropdown = document.getElementById("notification-dropdown");

                notificationBtn.addEventListener("click", function (event) {
                    event.stopPropagation();
                    notificationDropdown.classList.toggle("hidden");
                });

                // Profile Dropdown
                const profileBtn = document.getElementById("profile-btn");
                const profileDropdown = document.getElementById("profile-dropdown");

                profileBtn.addEventListener("click", function (event) {
                    event.stopPropagation();
                    profileDropdown.classList.toggle("hidden");
                });

                // Click Outside to Close Modals
                document.addEventListener("click", function (event) {
                    if (!notificationBtn.contains(event.target)) {
                        notificationDropdown.classList.add("hidden");
                    }
                    if (!profileBtn.contains(event.target)) {
                        profileDropdown.classList.add("hidden");
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
        // Select all dropdown toggle elements
        const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener("click", function () {
                // Find the next sibling (dropdown menu)
                const dropdownMenu = this.nextElementSibling;

                // Toggle visibility
                dropdownMenu.classList.toggle("hidden");

                // Rotate chevron icon
                const icon = this.querySelector(".chevron-icon");
                icon.classList.toggle("rotate-90");
            });
        });
    });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>
    </body>
    </html>
    <?php
}
?>
