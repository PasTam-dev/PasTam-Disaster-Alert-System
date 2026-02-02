@extends('layouts.plain')
@section('title', 'Pasong Tamo Evacuation Map')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    
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
                <div class="flex flex-wrap gap-2">
                    <button id="find-me" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition flex items-center">
                        <i class="fas fa-location-dot mr-2"></i> Find My Location
                    </button>
                    <button id="fullscreen-btn" class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600 transition flex items-center">
                        <i class="fas fa-expand mr-2"></i> Fullscreen
                    </button>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Map Controls -->
                <div class="p-4 border-b">
                    <div class="flex flex-wrap gap-2 justify-between items-center">
                        <div class="flex flex-wrap gap-2">
                            <button id="toggle-satellite" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition flex items-center">
                                <i class="fas fa-satellite mr-2"></i> Satellite View
                            </button>
                            <button id="toggle-street" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm hover:bg-gray-300 transition flex items-center">
                                <i class="fas fa-road mr-2"></i> Street View
                            </button>
                            <button id="toggle-terrain" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm hover:bg-gray-300 transition flex items-center">
                                <i class="fas fa-mountain mr-2"></i> Terrain View
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button id="toggle-flood" class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm hover:bg-blue-200 transition flex items-center">
                                <i class="fas fa-water mr-2"></i> Flood Zones
                            </button>
                            <button id="toggle-safe" class="px-4 py-2 bg-green-100 text-green-800 rounded-lg text-sm hover:bg-green-200 transition flex items-center">
                                <i class="fas fa-shield-alt mr-2"></i> Safe Zones
                            </button>
                            <button id="toggle-evacuation" class="px-4 py-2 bg-purple-100 text-purple-800 rounded-lg text-sm hover:bg-purple-200 transition flex items-center">
                                <i class="fas fa-school mr-2"></i> Centers
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Map Container -->
                <div id="map" class="shadow-lg"></div>
                <!-- Map Legend -->
                <div class="p-4 border-t">
                    <h3 class="font-semibold text-gray-800 mb-3">Map Legend</h3>
                    <p class="text-xs text-gray-500 mb-2">Tap a legend item to toggle its layer on the map.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="legend-item legend-toggle" data-layer="schools">
                            <div class="flex items-center">
                                <i class="fas fa-school text-blue-600 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Schools</span>
                                    <p class="text-xs text-gray-500">School-based facilities</p>
                                </div>
                            </div>
                            <span class="legend-status bg-blue-100 text-blue-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="evacCenters">
                            <div class="flex items-center">
                                <i class="fas fa-house-user text-purple-600 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Evacuation Centers</span>
                                    <p class="text-xs text-gray-500">Designated safe shelters</p>
                                </div>
                            </div>
                            <span class="legend-status bg-purple-100 text-purple-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="government">
                            <div class="flex items-center">
                                <i class="fas fa-landmark text-emerald-600 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Government</span>
                                    <p class="text-xs text-gray-500">Health & offices</p>
                                </div>
                            </div>
                            <span class="legend-status bg-emerald-100 text-emerald-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="barangayHalls">
                            <div class="flex items-center">
                                <i class="fas fa-building text-green-600 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Barangay Halls</span>
                                    <p class="text-xs text-gray-500">Barangay operations</p>
                                </div>
                            </div>
                            <span class="legend-status bg-green-100 text-green-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="flood">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">High Risk Flood Zones</span>
                                    <p class="text-xs text-gray-500">Red/orange overlays</p>
                                </div>
                            </div>
                            <span class="legend-status bg-red-100 text-red-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="safe">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-500 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Safe Areas</span>
                                    <p class="text-xs text-gray-500">Green safe overlays</p>
                                </div>
                            </div>
                            <span class="legend-status bg-green-100 text-green-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="elevated">
                            <div class="flex items-center">
                                <i class="fas fa-mountain text-sky-500 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Elevated Zones</span>
                                    <p class="text-xs text-gray-500">Higher ground areas</p>
                                </div>
                            </div>
                            <span class="legend-status bg-sky-100 text-sky-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>

                        <div class="legend-item legend-toggle" data-layer="user">
                            <div class="flex items-center">
                                <i class="fas fa-user text-blue-500 text-xl mr-3"></i>
                                <div>
                                    <span class="font-medium">Your Location</span>
                                    <p class="text-xs text-gray-500">Blue dot & route</p>
                                </div>
                            </div>
                            <span class="legend-status bg-blue-100 text-blue-700 text-[10px] uppercase tracking-wide">On</span>
                        </div>
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
                        <button onclick="focusOnLocation(14.67398, 121.04673, 'Pasong Tamo Elementary School')" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button onclick="showDirections(14.67398, 121.04673, 'Pasong Tamo Elementary School')" 
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
                        <button onclick="focusOnLocation(14.67475, 121.04812, 'Barangay Hall')" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button onclick="showDirections(14.67475, 121.04812, 'Barangay Hall')" 
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
                        <button onclick="focusOnLocation(14.67610, 121.04980, 'Community Center')" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> View on Map
                        </button>
                        <button onclick="showDirections(14.67610, 121.04980, 'Community Center')" 
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

   

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        let map;
        let floodLayer, safeLayer;
        let elevatedLayer, barangayCoverageLayer;
        let currentLayer = 'satellite';
        let satelliteTiles, streetTiles, terrainTiles;
        let userLocation = null;
        let userMarker = null;
        let userLayer = null;
        let routeLayer = null;
        let centerMarkers = [];

        const layerStates = {
            schools: true,
            evacCenters: true,
            government: true,
            barangayHalls: true,
            flood: true,
            safe: true,
            elevated: true,
            user: true
        };
        
        // Evacuation centers data
        const evacuationCenters = [
            { 
                name: "Pasong Tamo Elementary School", 
                lat: 14.67398, 
                lng: 121.04673, 
                capacity: 500, 
                current: 300, 
                type: "school", 
                color: "blue",
                facilities: ["Food", "Water", "Beds", "Medical"],
                contact: "(02) 111-1111",
                // Appears as both a school and a primary evacuation center
                categories: ["schools", "evacCenters"]
            },
            { 
                name: "Barangay Hall", 
                lat: 14.67475, 
                lng: 121.04812, 
                capacity: 200, 
                current: 80, 
                type: "government", 
                color: "green",
                facilities: ["Emergency Comms", "Medical", "Coordination"],
                contact: "(02) 123-4567",
                // Core government evacuation facility and barangay hall
                categories: ["evacCenters", "government", "barangayHalls"]
            },
            { 
                name: "Community Center", 
                lat: 14.67610, 
                lng: 121.04980, 
                capacity: 300, 
                current: 90, 
                type: "community", 
                color: "purple",
                facilities: ["Family Space", "Accessible", "Children Area"],
                contact: "(02) 222-2222",
                // Multi-purpose community evacuation site
                categories: ["evacCenters"]
            },
            { 
                name: "Health Center", 
                lat: 14.6670, 
                lng: 121.0300, 
                capacity: 150, 
                current: 30, 
                type: "health", 
                color: "red",
                facilities: ["Medical Staff", "Medicine", "Emergency Care"],
                contact: "(02) 333-3333",
                // Government-run health facility
                categories: ["government"]
            },
            { 
                name: "Gymnasium", 
                lat: 14.6690, 
                lng: 121.0340, 
                capacity: 400, 
                current: 120, 
                type: "sports", 
                color: "orange",
                facilities: ["Large Space", "Parking", "Basic Amenities"],
                contact: "(02) 444-4444",
                // Large-capacity secondary evacuation facility
                categories: ["evacCenters"]
            }
        ];

        // Initialize the map
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            setupEventListeners();
            
            // Show alert after 2 seconds
            setTimeout(() => {
                const alertBanner = document.getElementById('alert-banner');
                const alertText = document.getElementById('alert-text');
                alertText.textContent = 'WEATHER ADVISORY: Heavy rainfall expected in the next 24 hours. Stay alert and prepare for possible flooding.';
                alertBanner.classList.remove('hidden');
            }, 2000);
        });

        function initializeMap() {
            // Barangay Pasong Tamo center coordinates (Quezon City)
            const pasongTamo = [14.682138, 121.059137];
            
            // Initialize map with basic controls
            map = L.map('map').setView(pasongTamo, 15);
            
            // Add scale control
            L.control.scale({ imperial: false }).addTo(map);
            
            // Create tile layers
            satelliteTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles © Esri',
                maxZoom: 19
            }).addTo(map);
            
            streetTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            });
            
            terrainTiles = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenTopoMap contributors',
                maxZoom: 17
            });
            
            // Create layers
            createEvacuationCenters();
            createHazardZones();
            createElevatedZones();
            createBarangayCoverage();
            
            // Add simple fullscreen functionality
            setupFullscreen();
        }
        
        function createEvacuationCenters() {
            centerMarkers = [];

            evacuationCenters.forEach(center => {
                // Create custom icon based on type
                let iconHtml, iconColor;
                switch(center.type) {
                    case 'school':
                        iconHtml = '<i class="fas fa-school"></i>';
                        iconColor = '#3B82F6';
                        break;
                    case 'government':
                        iconHtml = '<i class="fas fa-building"></i>';
                        iconColor = '#10B981';
                        break;
                    case 'community':
                        iconHtml = '<i class="fas fa-home"></i>';
                        iconColor = '#8B5CF6';
                        break;
                    case 'health':
                        iconHtml = '<i class="fas fa-hospital"></i>';
                        iconColor = '#EF4444';
                        break;
                    default:
                        iconHtml = '<i class="fas fa-map-marker"></i>';
                        iconColor = '#F59E0B';
                }
                
                const icon = L.divIcon({
                    html: `<div class="bg-white rounded-full p-2 shadow-lg" style="border: 3px solid ${iconColor};">
                             <div style="color: ${iconColor}; font-size: 20px;">${iconHtml}</div>
                           </div>`,
                    className: 'custom-div-icon',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });
                
                const percentage = Math.round((center.current / center.capacity) * 100);
                const capacityColor = percentage > 80 ? '#EF4444' : percentage > 50 ? '#F59E0B' : '#10B981';
                
                const popupHtml = createCenterPopup(center, iconColor, percentage, capacityColor);

                const marker = L.marker([center.lat, center.lng], { icon: icon })
                    .bindPopup(popupHtml);

                centerMarkers.push({
                    marker,
                    categories: center.categories || ['evacCenters']
                });
            });

            updateCenterMarkersVisibility();
        }

        function createCenterPopup(center, iconColor, percentage, capacityColor) {
            // Primary evacuation centers with specific required content
            if (center.name === "Pasong Tamo Elementary School") {
                return `
                    <div class="custom-popup" style="min-width: 280px;">
                        <h3 class="font-bold text-lg mb-1" style="color: ${iconColor}">Pasong Tamo Elementary School</h3>
                        <p class="text-sm text-gray-600 mb-3">Main Evacuation Center</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Capacity:</span>
                                <span class="font-bold">500 people</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Currently:</span>
                                <span class="font-bold">300 (60% full)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full" style="width: 60%; background-color: ${capacityColor};"></div>
                            </div>
                            <ul class="mt-3 text-sm text-gray-700 space-y-1">
                                <li>Food supplies available</li>
                                <li>Sleeping facilities</li>
                            </ul>
                        </div>
                    </div>
                `;
            }

            if (center.name === "Barangay Hall") {
                return `
                    <div class="custom-popup" style="min-width: 280px;">
                        <h3 class="font-bold text-lg mb-1" style="color: ${iconColor}">Barangay Hall</h3>
                        <p class="text-sm text-gray-600 mb-3">Emergency Operations Center</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Capacity:</span>
                                <span class="font-bold">200 people</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Currently:</span>
                                <span class="font-bold">80 (40% full)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full" style="width: 40%; background-color: ${capacityColor};"></div>
                            </div>
                            <ul class="mt-3 text-sm text-gray-700 space-y-1">
                                <li>Emergency communications</li>
                                <li>Medical station</li>
                            </ul>
                        </div>
                    </div>
                `;
            }

            if (center.name === "Community Center") {
                return `
                    <div class="custom-popup" style="min-width: 280px;">
                        <h3 class="font-bold text-lg mb-1" style="color: ${iconColor}">Community Center</h3>
                        <p class="text-sm text-gray-600 mb-3">Multi-purpose Facility</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Capacity:</span>
                                <span class="font-bold">300 people</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Currently:</span>
                                <span class="font-bold">90 (30% full)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full" style="width: 30%; background-color: ${capacityColor};"></div>
                            </div>
                            <ul class="mt-3 text-sm text-gray-700 space-y-1">
                                <li>Family-friendly space</li>
                                <li>Accessible facility</li>
                            </ul>
                        </div>
                    </div>
                `;
            }

            // Fallback generic popup for other facilities
            return `
                <div class="custom-popup" style="min-width: 280px;">
                    <h3 class="font-bold text-lg mb-2" style="color: ${iconColor}">${center.name}</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Capacity:</span>
                            <span class="font-bold">${center.capacity} people</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Currently:</span>
                            <span class="font-bold">${center.current} (${percentage}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="h-2 rounded-full" style="width: ${percentage}%; background-color: ${capacityColor};"></div>
                        </div>
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-1">Facilities:</p>
                            <div class="flex flex-wrap gap-1">
                                ${center.facilities.map(f => `<span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">${f}</span>`).join('')}
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-700">Contact: ${center.contact}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateCenterMarkersVisibility() {
            centerMarkers.forEach(entry => {
                const shouldBeVisible = entry.categories.some(cat => layerStates[cat]);
                const isOnMap = map.hasLayer(entry.marker);

                if (shouldBeVisible && !isOnMap) {
                    entry.marker.addTo(map);
                } else if (!shouldBeVisible && isOnMap) {
                    map.removeLayer(entry.marker);
                }
            });
        }
        
        function createHazardZones() {
            // Flood-prone areas
            const floodZone1 = L.polygon([
                [14.6640, 121.0310],
                [14.6640, 121.0340],
                [14.6620, 121.0340],
                [14.6620, 121.0310]
            ], {
                color: '#DC2626',
                fillColor: '#EF4444',
                fillOpacity: 0.3,
                weight: 3,
                dashArray: '5, 5'
            }).bindPopup(`
                <div class="custom-popup">
                    <h3 class="font-bold text-red-600">High Flood Risk Area</h3>
                    <p class="text-sm mt-2">⚠️ Avoid this area during heavy rainfall</p>
                    <p class="text-xs text-gray-600 mt-1">Prone to flash flooding</p>
                </div>
            `);
            
            const floodZone2 = L.polygon([
                [14.6690, 121.0360],
                [14.6690, 121.0385],
                [14.6675, 121.0385],
                [14.6675, 121.0360]
            ], {
                color: '#F97316',
                fillColor: '#F59E0B',
                fillOpacity: 0.3,
                weight: 3,
                dashArray: '5, 5'
            }).bindPopup(`
                <div class="custom-popup">
                    <h3 class="font-bold text-orange-600">Moderate Flood Risk Area</h3>
                    <p class="text-sm mt-2">⚠️ Exercise caution during storms</p>
                    <p class="text-xs text-gray-600 mt-1">May flood during heavy rain</p>
                </div>
            `);
            
            floodLayer = L.layerGroup([floodZone1, floodZone2]).addTo(map);
            
            // Safe zones
            const safeZone1 = L.circle([14.67398, 121.04673], {
                color: '#10B981',
                fillColor: '#34D399',
                fillOpacity: 0.2,
                radius: 200,
                weight: 2
            }).bindPopup(`
                <div class="custom-popup">
                    <h3 class="font-bold text-green-600">Safe Zone - High Ground</h3>
                    <p class="text-sm mt-2">✅ Recommended evacuation area</p>
                    <p class="text-xs text-gray-600 mt-1">Elevated and secure location</p>
                </div>
            `);
            
            const safeZone2 = L.circle([14.67610, 121.04980], {
                color: '#10B981',
                fillColor: '#34D399',
                fillOpacity: 0.2,
                radius: 180,
                weight: 2
            }).bindPopup(`
                <div class="custom-popup">
                    <h3 class="font-bold text-green-600">Safe Zone - Community Area</h3>
                    <p class="text-sm mt-2">✅ Secure location with facilities</p>
                    <p class="text-xs text-gray-600 mt-1">Equipped for emergencies</p>
                </div>
            `);
            
            safeLayer = L.layerGroup([safeZone1, safeZone2]).addTo(map);
        }

        function createElevatedZones() {
            // Elevated / high-ground areas (separate from general safe zones)
            const elevated1 = L.polygon([
                [14.6675, 121.0315],
                [14.6675, 121.0330],
                [14.6665, 121.0330],
                [14.6665, 121.0315]
            ], {
                color: '#0EA5E9',
                fillColor: '#38BDF8',
                fillOpacity: 0.18,
                weight: 2,
                dashArray: '4, 4'
            }).bindPopup(`
                <div class="custom-popup">
                    <h3 class="font-bold text-sky-600">Elevated Zone</h3>
                    <p class="text-sm mt-2">Area with relatively higher elevation, less prone to flooding.</p>
                </div>
            `);

            elevatedLayer = L.layerGroup([elevated1]).addTo(map);
        }

        function createBarangayCoverage() {
            // Approximate barangay coverage for Barangay Pasong Tamo, Quezon City
            const coverageCircle = L.circle([14.682138, 121.059137], {
                color: '#16A34A',
                fillColor: '#22C55E',
                fillOpacity: 0.12,
                radius: 1100,
                weight: 2
            }).bindPopup(`
                <div class="custom-popup">
                    <h3 class="font-bold text-green-600">Barangay Pasong Tamo Coverage</h3>
                    <p class="text-sm mt-2">Approximate geographic extent of Barangay Pasong Tamo, Quezon City.</p>
                    <p class="text-xs text-gray-600 mt-1">Use this overlay to understand which areas fall under barangay response.</p>
                </div>
            `);

            barangayCoverageLayer = L.layerGroup([coverageCircle]).addTo(map);
        }
        
        function setupEventListeners() {
            // Alert close button
            document.getElementById('close-alert').addEventListener('click', () => {
                document.getElementById('alert-banner').classList.add('hidden');
            });
            
            // Map view toggles
            document.getElementById('toggle-satellite').addEventListener('click', () => toggleMapView('satellite'));
            document.getElementById('toggle-street').addEventListener('click', () => toggleMapView('street'));
            document.getElementById('toggle-terrain').addEventListener('click', () => toggleMapView('terrain'));
            
            // Header layer toggles (keep buttons but wire to shared layer state)
            let floodVisible = layerStates.flood;
            document.getElementById('toggle-flood').addEventListener('click', function() {
                floodVisible = !floodVisible;
                setLayerState('flood', floodVisible);
                updateButtonState(this, floodVisible, 'blue');
            });
            
            let safeVisible = layerStates.safe;
            document.getElementById('toggle-safe').addEventListener('click', function() {
                safeVisible = !safeVisible;
                setLayerState('safe', safeVisible);
                updateButtonState(this, safeVisible, 'green');
            });
            
            let evacuationVisible = layerStates.evacCenters;
            document.getElementById('toggle-evacuation').addEventListener('click', function() {
                evacuationVisible = !evacuationVisible;
                setLayerState('evacCenters', evacuationVisible);
                updateButtonState(this, evacuationVisible, 'purple');
            });
            
            // Find my location
            document.getElementById('find-me').addEventListener('click', findMyLocation);
            
            // Optional search functionality (only if search UI exists)
            const searchInput = document.getElementById('search-input');
            const searchResults = document.getElementById('search-results');
            
            if (searchInput && searchResults) {
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();
                    if (query.length < 2) {
                        searchResults.classList.add('hidden');
                        return;
                    }
                    
                    const results = evacuationCenters.filter(center => 
                        center.name.toLowerCase().includes(query)
                    );
                    
                    if (results.length > 0) {
                        searchResults.innerHTML = results.map(center => `
                            <div class="p-3 border-b border-gray-200 hover:bg-gray-100 cursor-pointer"
                                 onclick="focusOnLocation(${center.lat}, ${center.lng}, '${center.name.replace(/'/g, "\\'")}')">
                                <div class="font-medium">${center.name}</div>
                                <div class="text-sm text-gray-600">Evacuation Center</div>
                            </div>
                        `).join('');
                        searchResults.classList.remove('hidden');
                    } else {
                        searchResults.innerHTML = '<div class="p-3 text-gray-600">No results found</div>';
                        searchResults.classList.remove('hidden');
                    }
                });
                
                // Close search results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
                
                // Close search on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        searchResults.classList.add('hidden');
                    }
                });
            }

            // Legend toggles
            const legendItems = document.querySelectorAll('.legend-toggle');
            legendItems.forEach(item => {
                const layerKey = item.getAttribute('data-layer');
                item.addEventListener('click', () => {
                    const newValue = !layerStates[layerKey];
                    setLayerState(layerKey, newValue);
                });
            });
        }

        function setLayerState(layerKey, isVisible) {
            layerStates[layerKey] = isVisible;

            switch (layerKey) {
                case 'flood':
                    if (floodLayer) {
                        isVisible ? floodLayer.addTo(map) : map.removeLayer(floodLayer);
                    }
                    break;
                case 'safe':
                    if (safeLayer) {
                        isVisible ? safeLayer.addTo(map) : map.removeLayer(safeLayer);
                    }
                    if (barangayCoverageLayer) {
                        isVisible ? barangayCoverageLayer.addTo(map) : map.removeLayer(barangayCoverageLayer);
                    }
                    break;
                case 'elevated':
                    if (elevatedLayer) {
                        isVisible ? elevatedLayer.addTo(map) : map.removeLayer(elevatedLayer);
                    }
                    break;
                case 'user':
                    updateUserLayerVisibility();
                    break;
                default:
                    // Marker-based logical layers (schools, evac centers, government, barangay halls)
                    updateCenterMarkersVisibility();
            }

            updateLegendVisual(layerKey, isVisible);
        }

        function updateLegendVisual(layerKey, isVisible) {
            const legendItem = document.querySelector(`.legend-toggle[data-layer="${layerKey}"]`);
            if (!legendItem) return;

            const statusEl = legendItem.querySelector('.legend-status');
            if (isVisible) {
                legendItem.classList.remove('inactive');
                if (statusEl) {
                    statusEl.textContent = 'On';
                }
            } else {
                legendItem.classList.add('inactive');
                if (statusEl) {
                    statusEl.textContent = 'Off';
                }
            }
        }
        
        function setupFullscreen() {
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const mapContainer = document.getElementById('map');
            
            fullscreenBtn.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    if (mapContainer.requestFullscreen) {
                        mapContainer.requestFullscreen();
                    } else if (mapContainer.webkitRequestFullscreen) {
                        mapContainer.webkitRequestFullscreen();
                    } else if (mapContainer.msRequestFullscreen) {
                        mapContainer.msRequestFullscreen();
                    }
                    fullscreenBtn.innerHTML = '<i class="fas fa-compress mr-2"></i> Exit Fullscreen';
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                    fullscreenBtn.innerHTML = '<i class="fas fa-expand mr-2"></i> Fullscreen';
                }
            });
            
            // Update button text when exiting fullscreen via other methods
            document.addEventListener('fullscreenchange', updateFullscreenButton);
            document.addEventListener('webkitfullscreenchange', updateFullscreenButton);
            document.addEventListener('msfullscreenchange', updateFullscreenButton);
            
            function updateFullscreenButton() {
                if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                    fullscreenBtn.innerHTML = '<i class="fas fa-expand mr-2"></i> Fullscreen';
                }
            }
        }
        
        function toggleMapView(viewType) {
            if (currentLayer === viewType) return;
            
            // Remove current layer
            switch(currentLayer) {
                case 'satellite': map.removeLayer(satelliteTiles); break;
                case 'street': map.removeLayer(streetTiles); break;
                case 'terrain': map.removeLayer(terrainTiles); break;
            }
            
            // Add new layer
            switch(viewType) {
                case 'satellite': satelliteTiles.addTo(map); break;
                case 'street': streetTiles.addTo(map); break;
                case 'terrain': terrainTiles.addTo(map); break;
            }
            
            // Update button states
            ['satellite', 'street', 'terrain'].forEach(type => {
                const btn = document.getElementById(`toggle-${type}`);
                if (type === viewType) {
                    btn.classList.remove('bg-gray-200', 'text-gray-800');
                    btn.classList.add('bg-blue-500', 'text-white');
                } else {
                    btn.classList.remove('bg-blue-500', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-800');
                }
            });
            
            currentLayer = viewType;
        }
        
        function updateButtonState(button, isActive, color) {
            if (isActive) {
                button.classList.remove(`bg-${color}-100`, `text-${color}-800`);
                button.classList.add(`bg-${color}-500`, 'text-white');
            } else {
                button.classList.add(`bg-${color}-100`, `text-${color}-800`);
                button.classList.remove(`bg-${color}-500`, 'text-white');
            }
        }
        
        function focusOnLocation(lat, lng, name = 'Location') {
            map.setView([lat, lng], 17);
            
            // Show notification
            showNotification(`Zoomed to ${name}`);
            
            // Scroll to map section
            document.getElementById('map-section').scrollIntoView({ behavior: 'smooth' });
        }
        
        function findMyLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            
            const findMeBtn = document.getElementById('find-me');
            findMeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Finding...';
            findMeBtn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    userLocation = { lat, lng };
                    
                    // Ensure user layer exists
                    if (!userLayer) {
                        userLayer = L.layerGroup();
                        if (layerStates.user) {
                            userLayer.addTo(map);
                        }
                    }

                    // Remove previous user marker
                    if (userMarker && userLayer) {
                        userLayer.removeLayer(userMarker);
                    }
                    
                    // Add new user marker with animation to the user layer
                    userMarker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            html: '<div class="user-marker bg-blue-600 rounded-full p-3 shadow-lg"><i class="fas fa-user text-white text-xl"></i></div>',
                            className: 'custom-div-icon',
                            iconSize: [40, 40],
                            iconAnchor: [20, 40]
                        })
                    }).bindPopup('<div class="custom-popup"><h3 class="font-bold text-blue-600">Your Location</h3><p class="text-sm">You are here</p></div>');

                    if (layerStates.user && userLayer) {
                        userLayer.addLayer(userMarker);
                    }

                    userMarker.openPopup();
                    
                    // Center map on user
                    map.setView([lat, lng], 16);
                    
                    // Find nearest evacuation center
                    const nearest = findNearestCenter(userLocation);
                    if (nearest) {
                        const distance = calculateDistance(lat, lng, nearest.lat, nearest.lng);
                        setTimeout(() => {
                            showNotification(`Nearest evacuation center: ${nearest.name} (${Math.round(distance * 1000)} meters away)`);
                            drawRouteToNearestCenter();
                        }, 1000);
                    }
                    
                    findMeBtn.innerHTML = '<i class="fas fa-location-dot mr-2"></i> Found You!';
                    setTimeout(() => {
                        findMeBtn.innerHTML = '<i class="fas fa-location-dot mr-2"></i> Find My Location';
                        findMeBtn.disabled = false;
                    }, 2000);
                },
                function(error) {
                    alert('Unable to get your location. Please check your browser settings.');
                    findMeBtn.innerHTML = '<i class="fas fa-location-dot mr-2"></i> Find My Location';
                    findMeBtn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
        
        function findNearestCenter(userLoc) {
            let nearest = null;
            let minDistance = Infinity;
            
            evacuationCenters.forEach(center => {
                const distance = calculateDistance(
                    userLoc.lat, userLoc.lng,
                    center.lat, center.lng
                );
                
                if (distance < minDistance) {
                    minDistance = distance;
                    nearest = center;
                }
            });
            
            return nearest;
        }
        
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }
        
        function drawRouteToNearestCenter() {
            if (!userLocation) return;
            
            // Ensure user layer exists
            if (!userLayer) {
                userLayer = L.layerGroup();
                if (layerStates.user) {
                    userLayer.addTo(map);
                }
            }

            // Clear existing routes from user layer
            if (routeLayer && userLayer) {
                userLayer.removeLayer(routeLayer);
            }
            
            const nearest = findNearestCenter(userLocation);
            if (!nearest) return;
            
            // Create a simple line for demonstration
            const route = L.polyline([
                [userLocation.lat, userLocation.lng],
                [nearest.lat, nearest.lng]
            ], {
                color: '#8B5CF6',
                weight: 4,
                opacity: 0.7,
                dashArray: '10, 10'
            });

            routeLayer = L.layerGroup([route]);
            if (layerStates.user && userLayer) {
                userLayer.addLayer(routeLayer);
            }
            
            // Show the route button as active
            document.getElementById('toggle-evacuation').classList.remove('bg-purple-100', 'text-purple-800');
            document.getElementById('toggle-evacuation').classList.add('bg-purple-500', 'text-white');
        }
        
        function showDirections(destLat, destLng, destName) {
            if (!userLocation) {
                alert('Please enable location services first by clicking "Find My Location"');
                return;
            }
            
            // For demonstration, we'll just draw a line and show info
            if (!userLayer) {
                userLayer = L.layerGroup();
                if (layerStates.user) {
                    userLayer.addTo(map);
                }
            }

            if (routeLayer && userLayer) {
                userLayer.removeLayer(routeLayer);
            }
            
            const route = L.polyline([
                [userLocation.lat, userLocation.lng],
                [destLat, destLng]
            ], {
                color: '#10B981',
                weight: 5,
                opacity: 0.7
            });

            routeLayer = L.layerGroup([route]);
            if (layerStates.user && userLayer) {
                userLayer.addLayer(routeLayer);
            }
            
            // Center map on route
            const bounds = L.latLngBounds([
                [userLocation.lat, userLocation.lng],
                [destLat, destLng]
            ]);
            map.fitBounds(bounds, { padding: [50, 50] });
            
            showNotification(`Route to ${destName} displayed`);
        }
        
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-1000 animate-fade-in';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('animate-fade-out');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fade-out {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-10px); }
            }
            .animate-fade-in { animation: fade-in 0.3s ease-out; }
            .animate-fade-out { animation: fade-out 0.3s ease-out; }
        `;
        document.head.appendChild(style);

        function updateUserLayerVisibility() {
            if (!userLayer) {
                // Nothing to toggle yet
                return;
            }

            if (layerStates.user) {
                if (!map.hasLayer(userLayer)) {
                    userLayer.addTo(map);
                }
            } else if (map.hasLayer(userLayer)) {
                map.removeLayer(userLayer);
            }
        }
    </script>
@endsection
