@extends('layouts.plain')
@section('title', 'Pasong Tamo Evacuation Map')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .alert-banner {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        #map {
            height: 600px;
            width: 100%;
            border-radius: 0.5rem;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .custom-popup {
            padding: 15px;
            min-width: 250px;
        }
        .evacuation-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .evacuation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            margin-bottom: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: background-color 0.15s ease, box-shadow 0.15s ease, transform 0.08s ease;
        }
        .legend-item:hover {
            background: #F9FAFB;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            transform: translateY(-1px);
        }
        .legend-item.inactive {
            opacity: 0.6;
            background: #F3F4F6;
        }
        .legend-status {
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 9999px;
        }
        .capacity-indicator {
            height: 8px;
            border-radius: 4px;
            margin-top: 4px;
        }
        .capacity-low { background-color: #10B981; }
        .capacity-medium { background-color: #F59E0B; }
        .capacity-high { background-color: #EF4444; }
        .search-box {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            width: 300px;
        }
        .user-marker {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
        .route-line {
            stroke-dasharray: 10, 10;
            animation: dash 1s linear infinite;
        }
        @keyframes dash {
            to {
                stroke-dashoffset: -20;
            }
        }
        /* Fullscreen button styling */
        .fullscreen-btn {
            background: white;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 4px;
            padding: 6px 12px;
            font-weight: bold;
            cursor: pointer;
        }
        .fullscreen-btn:hover {
            background: #f4f4f4;
        }

        @media (max-width: 768px) {
            #map {
                height: 420px;
            }
        }
    </style>


    <!-- Emergency Alert Banner -->
    <div id="alert-banner" class="hidden">
        <div class="bg-red-600 text-white py-3 px-4">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                    <div>
                        <p class="font-bold" id="alert-text"></p>
                        <p class="text-sm text-red-200">Last updated: Just now</p>
                    </div>
                </div>
                <button id="close-alert" class="text-white text-2xl">&times;</button>
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="mb-12 text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Community Disaster Preparedness</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Stay informed and prepared with real-time disaster alerts, satellite maps, and evacuation resources for Pasong Tamo.
            </p>
        </section>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600 mb-1">5</div>
                <p class="text-gray-700 text-sm">Evacuation Centers</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600 mb-1">85%</div>
                <p class="text-gray-700 text-sm">Safety Coverage</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-1">2</div>
                <p class="text-gray-700 text-sm">Active Alerts</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600 mb-1">1,550+</div>
                <p class="text-gray-700 text-sm">Capacity Total</p>
            </div>
        </div>

        <!-- Interactive Map Section -->
        <section id="map-section" class="mb-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0 flex items-center">
                    <i class="fas fa-map-marked-alt text-blue-500 mr-2"></i> Interactive Evacuation Map
                </h2>
                <div class="flex gap-2">
                    <button type="button"
                            onclick="openMiniMapForUser()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm flex items-center justify-center">
                        <i class="fas fa-location-dot mr-2"></i> Find My Location
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b">
                    <p class="text-sm text-gray-700">
                        This map is powered by HazardHunterPH and is intended for assessing hazards in and around Pasong Tamo, Quezon City.
                        Double-click or tap on the map to start a hazard assessment.
                    </p>
                </div>
                <div class="w-full" style="height: 600px;">
                    <iframe
                        src="https://hazardhunter.georisk.gov.ph/map"
                        title="HazardHunterPH Map - Pasong Tamo, Quezon City"
                        class="w-full h-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>

        <!-- Evacuation Centers List -->
        <section id="evacuation-list" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Evacuation Centers & Facilities</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Evacuation Center 1 -->
                <div class="evacuation-card bg-white rounded-xl shadow-md p-6 border-t-4 border-blue-500">
                    <div class="flex items-start mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-school text-blue-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Pasong Tamo Elementary School</h3>
                            <p class="text-gray-600">Main Evacuation Center</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-users text-gray-400 mr-3"></i>
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm">Capacity</span>
                                    <span class="text-sm font-bold">500 people</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 60%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Currently: 300 (60% full)</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-utensils text-gray-400 mr-3"></i>
                            <span class="text-gray-700">Food supplies available</span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-bed text-gray-400 mr-3"></i>
                            <span class="text-gray-700">Sleeping facilities</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="openMiniMap(14.67398,121.04673,'Pasong Tamo Elementary School')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button type="button"
                                onclick="openMiniMap(14.67398,121.04673,'Pasong Tamo Elementary School')"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-directions mr-2"></i> Directions
                        </button>
                    </div>
                </div>
                
                <!-- Barangay Hall -->
                <div class="evacuation-card bg-white rounded-xl shadow-md p-6 border-t-4 border-green-500">
                    <div class="flex items-start mb-4">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-building text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Barangay Hall</h3>
                            <p class="text-gray-600">Emergency Operations Center</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-users text-gray-400 mr-3"></i>
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm">Capacity</span>
                                    <span class="text-sm font-bold">200 people</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 40%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Currently: 80 (40% full)</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-phone-alt text-gray-400 mr-3"></i>
                            <span class="text-gray-700">Emergency communications</span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-first-aid text-gray-400 mr-3"></i>
                            <span class="text-gray-700">Medical station</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="openMiniMap(14.6747884,121.0476176,'Pasong Tamo Barangay Hall')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button type="button"
                                onclick="openMiniMap(14.6747884,121.0476176,'Pasong Tamo Barangay Hall')"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-directions mr-2"></i> Directions
                        </button>
                    </div>
                </div>
                
                <!-- Community Center -->
                <div class="evacuation-card bg-white rounded-xl shadow-md p-6 border-t-4 border-purple-500">
                    <div class="flex items-start mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-home text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Community Center</h3>
                            <p class="text-gray-600">Multi-purpose Facility</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-users text-gray-400 mr-3"></i>
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm">Capacity</span>
                                    <span class="text-sm font-bold">300 people</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Currently: 90 (30% full)</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-child text-gray-400 mr-3"></i>
                            <span class="text-gray-700">Family-friendly space</span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-wheelchair text-gray-400 mr-3"></i>
                            <span class="text-gray-700">Accessible facility</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="openMiniMap(14.67610,121.04980,'Community Center')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button type="button"
                                onclick="openMiniMap(14.67610,121.04980,'Community Center')"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-directions mr-2"></i> Directions
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Emergency Information -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">What to Bring (Emergency Go-Bag)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Essentials -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-backpack text-orange-600 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-orange-800">Basic Essentials</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-orange-500 mt-1 mr-2"></i>
                            <span>Drinking water (at least 3 days supply)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-orange-500 mt-1 mr-2"></i>
                            <span>Non-perishable food</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-orange-500 mt-1 mr-2"></i>
                            <span>Flashlight and extra batteries</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Personal Items -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-user-shield text-blue-600 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-blue-800">Personal Items</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span>Extra clothes and face mask</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span>Personal hygiene items</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mt-1 mr-2"></i>
                            <span>Important documents (ID, certificates)</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Emergency & Medical -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-first-aid text-green-600 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-green-800">Emergency & Medical</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>First aid kit</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Maintenance medicines</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                            <span>Whistle, power bank, and phone charger</span>
                        </li>
                    </ul>
                </div>

            </div>
        </section>

        <!-- Mini Google Map Modal -->
        <div id="mini-map-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-xl">
                <div class="flex items-center justify-between px-4 py-2 border-b">
                    <h3 id="mini-map-title" class="text-lg font-semibold text-gray-800">Location</h3>
                    <button type="button" onclick="closeMiniMap()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
                </div>
                <div class="w-full h-80">
                    <iframe id="mini-map-frame" class="w-full h-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertBanner = document.getElementById('alert-banner');
            const alertText = document.getElementById('alert-text');
            const closeAlert = document.getElementById('close-alert');

            if (alertBanner && alertText) {
                alertText.textContent = 'WEATHER ADVISORY: Heavy rainfall expected in the next 24 hours. Stay alert and prepare for possible flooding.';
                alertBanner.classList.remove('hidden');
            }

            if (closeAlert) {
                closeAlert.addEventListener('click', function () {
                    alertBanner.classList.add('hidden');
                });
            }
        });

        function openMiniMap(lat, lng, title) {
            const modal = document.getElementById('mini-map-modal');
            const frame = document.getElementById('mini-map-frame');
            const titleEl = document.getElementById('mini-map-title');
            if (!modal || !frame) return;
            const url = 'https://www.google.com/maps?q=' + lat + ',' + lng + '&z=17&output=embed';
            frame.src = url;
            if (titleEl && title) {
                titleEl.textContent = title;
            }
            modal.classList.remove('hidden');
        }

        function closeMiniMap() {
            const modal = document.getElementById('mini-map-modal');
            const frame = document.getElementById('mini-map-frame');
            if (!modal || !frame) return;
            modal.classList.add('hidden');
            frame.src = '';
        }

        function openMiniMapForUser() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    openMiniMap(lat, lng, 'Your Location');
                },
                function () {
                    alert('Unable to get your location. Please check your browser settings.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    </script>
@endsection
