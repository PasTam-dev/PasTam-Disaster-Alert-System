@extends('layouts.plain')
@section('title', 'Pasong Tamo Evacuation Map')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
    
    <style>
        .alert-banner {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        #leaflet-map {
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
            #leaflet-map {
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
                <div class="flex gap-2 flex-wrap">
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        <button
                            type="button"
                            id="btn-evac-map"
                            onclick="setMapMode('evac')"
                            class="px-3 py-2 text-xs md:text-sm font-medium border border-blue-600 bg-blue-600 text-white rounded-l-lg hover:bg-blue-700 focus:outline-none">
                            <i class="fas fa-route mr-1"></i> Evacuation Map
                        </button>
                        <button
                            type="button"
                            id="btn-hazard-map"
                            onclick="setMapMode('hazard')"
                            class="px-3 py-2 text-xs md:text-sm font-medium border border-blue-600 bg-white text-blue-600 rounded-r-lg hover:bg-blue-50 focus:outline-none">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Hazard Map
                        </button>
                    </div>
                    <button type="button"
                            onclick="locateUserOnMap()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm flex items-center justify-center">
                        <i class="fas fa-location-dot mr-2"></i> Find My Location
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b">
                    <p class="text-sm text-gray-700">
                        Use the <span class="font-semibold">Evacuation Map</span> for navigation and directions within Pasong Tamo.
                        Switch to the <span class="font-semibold">Hazard Map</span> to view official multi-hazard information via HazardHunterPH.
                    </p>
                </div>
                <div class="w-full" style="height: 600px;">
                    <!-- Leaflet evacuation map (orientation, directions, current location) -->
                    <div id="leaflet-map-container" class="w-full h-full">
                        <div id="leaflet-map" class="w-full h-full"></div>
                    </div>

                    <!-- HazardHunterPH official hazard map -->
                    <div id="hazard-map-container" class="w-full h-full hidden">
                        <iframe
                            src="https://hazardhunter.georisk.gov.ph/map"
                            title="HazardHunterPH Map - Pasong Tamo, Quezon City"
                            class="w-full h-full border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
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
                                onclick="focusEvacCenter(14.67398,121.04673,'Pasong Tamo Elementary School')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button type="button"
                                onclick="routeToEvacCenter(14.67398,121.04673,'Pasong Tamo Elementary School')"
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
                                onclick="focusEvacCenter(14.6747884,121.0476176,'Pasong Tamo Barangay Hall')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button type="button"
                                onclick="routeToEvacCenter(14.6747884,121.0476176,'Pasong Tamo Barangay Hall')"
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
                                onclick="focusEvacCenter(14.67610,121.04980,'Community Center')"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button type="button"
                                onclick="routeToEvacCenter(14.67610,121.04980,'Community Center')"
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

    </main>
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin="">
    </script>
    <script>
        let leafletMap = null;
        let userMarker = null;
        let routeLine = null;
        let currentMapMode = 'evac';
        const centerMarkers = {};

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

            const leafletContainer = document.getElementById('leaflet-map');
            if (leafletContainer && typeof L !== 'undefined') {
                initializeLeafletMap();
            }
        });

        function initializeLeafletMap() {
            leafletMap = L.map('leaflet-map').setView([14.6745, 121.0475], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(leafletMap);

            const centers = [
                { key: 'elementary', lat: 14.67398, lng: 121.04673, title: 'Pasong Tamo Elementary School' },
                { key: 'barangay', lat: 14.6747884, lng: 121.0476176, title: 'Pasong Tamo Barangay Hall' },
                { key: 'community', lat: 14.67610, lng: 121.04980, title: 'Community Center' }
            ];

            centers.forEach(function (c) {
                const marker = L.marker([c.lat, c.lng]).addTo(leafletMap).bindPopup('<strong>' + c.title + '</strong>');
                centerMarkers[c.key] = marker;
            });
        }

        function setMapMode(mode) {
            currentMapMode = mode === 'hazard' ? 'hazard' : 'evac';

            const leafletContainer = document.getElementById('leaflet-map-container');
            const hazardContainer = document.getElementById('hazard-map-container');
            const btnEvac = document.getElementById('btn-evac-map');
            const btnHazard = document.getElementById('btn-hazard-map');

            if (!leafletContainer || !hazardContainer || !btnEvac || !btnHazard) {
                return;
            }

            if (currentMapMode === 'hazard') {
                leafletContainer.classList.add('hidden');
                hazardContainer.classList.remove('hidden');

                btnEvac.classList.remove('bg-blue-600', 'text-white');
                btnEvac.classList.add('bg-white', 'text-blue-600');

                btnHazard.classList.remove('bg-white', 'text-blue-600');
                btnHazard.classList.add('bg-blue-600', 'text-white');
            } else {
                hazardContainer.classList.add('hidden');
                leafletContainer.classList.remove('hidden');

                btnHazard.classList.remove('bg-blue-600', 'text-white');
                btnHazard.classList.add('bg-white', 'text-blue-600');

                btnEvac.classList.remove('bg-white', 'text-blue-600');
                btnEvac.classList.add('bg-blue-600', 'text-white');

                if (leafletMap) {
                    leafletMap.invalidateSize();
                }
            }
        }

        function locateUserOnMap() {
            setMapMode('evac');

            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (!leafletMap) return;

                    if (userMarker) {
                        leafletMap.removeLayer(userMarker);
                    }

                    userMarker = L.marker([lat, lng]).addTo(leafletMap).bindPopup('<strong>Your Location</strong>');
                    userMarker.openPopup();
                    leafletMap.setView([lat, lng], 17);
                },
                function () {
                    alert('Unable to get your location. Please check your browser settings.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }

        function focusEvacCenter(lat, lng, title) {
            setMapMode('evac');

            if (!leafletMap) return;

            leafletMap.setView([lat, lng], 17);

            Object.values(centerMarkers).forEach(function (marker) {
                const mLatLng = marker.getLatLng();
                if (Math.abs(mLatLng.lat - lat) < 0.0005 && Math.abs(mLatLng.lng - lng) < 0.0005) {
                    marker.openPopup();
                }
            });
        }

        function routeToEvacCenter(lat, lng, title) {
            setMapMode('evac');

            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                focusEvacCenter(lat, lng, title);
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    if (!leafletMap) return;

                    if (userMarker) {
                        leafletMap.removeLayer(userMarker);
                    }
                    if (routeLine) {
                        leafletMap.removeLayer(routeLine);
                    }

                    userMarker = L.marker([userLat, userLng]).addTo(leafletMap).bindPopup('<strong>Your Location</strong>');
                    focusEvacCenter(lat, lng, title);

                    routeLine = L.polyline([
                        [userLat, userLng],
                        [lat, lng]
                    ], {
                        color: '#2563eb',
                        weight: 4,
                        opacity: 0.8,
                        dashArray: '6, 6'
                    }).addTo(leafletMap);

                    const bounds = routeLine.getBounds();
                    bounds.extend([userLat, userLng]);
                    bounds.extend([lat, lng]);
                    leafletMap.fitBounds(bounds, { padding: [40, 40] });
                },
                function () {
                    alert('Unable to get your location. Please check your browser settings.');
                    focusEvacCenter(lat, lng, title);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    </script>
@endsection
