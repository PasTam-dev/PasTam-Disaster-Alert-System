@extends('layouts.plain')
@section('title', 'Emergency Contacts Directory')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4 {
            font-family: 'Poppins', sans-serif;
        }
        .emergency-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        .emergency-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .contact-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: contain;
            background-color: rgba(255,255,255,0.95);
            padding: 6px;
        }
        .urgent-red {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: white;
        }
        .hospital-blue {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
        }
        .fire-orange {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            color: white;
        }
        .police-dark {
            background: linear-gradient(135deg, #4B5563 0%, #374151 100%);
            color: white;
        }
        .disaster-green {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }
        .utility-purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            color: white;
        }
        .govt-yellow {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
        }
        .quick-call-btn {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .sticky-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        .emergency-banner {
            animation: flash 3s infinite;
        }
        @keyframes flash {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .search-highlight {
            background-color: #FEF3C7;
            padding: 2px 4px;
            border-radius: 4px;
        }
    </style>

    <!-- Header -->
    <header class="bg-white shadow-sm sticky-nav">
        <div class="container max-w-full px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-phone-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Philippines Emergency Contacts</h1>
                        <p class="text-gray-600">Complete Directory of Emergency Services</p>
                    </div>
                </div>
                
            </div>
        </div>
    </header>

    <!-- Search and Filter -->
    <div class="container max-w-full px-4 py-6">
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search emergency contacts, services, or locations..." 
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                    </div>
                </div>
                <div>
                    <select id="categoryFilter" class="w-full md:w-auto px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                        <option value="all">All Categories</option>
                        <option value="medical">Medical & Hospitals</option>
                        <option value="police">Police & Security</option>
                        <option value="fire">Fire & Rescue</option>
                        <option value="disaster">Disaster Response</option>
                        <option value="utility">Utilities & Services</option>
                        <option value="government">Government Agencies</option>
                    </select>
                </div>
            </div>
            
            <!-- Quick Filter Buttons -->
            <div class="flex flex-wrap gap-2 mt-4">
                <button class="filter-btn active px-4 py-2 rounded-full bg-red-100 text-red-800 hover:bg-red-200" data-category="urgent">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Urgent
                </button>
                <button class="filter-btn px-4 py-2 rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200" data-category="medical">
                    <i class="fas fa-hospital mr-2"></i> Medical
                </button>
                <button class="filter-btn px-4 py-2 rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200" data-category="police">
                    <i class="fas fa-shield-alt mr-2"></i> Police
                </button>
                <button class="filter-btn px-4 py-2 rounded-full bg-orange-100 text-orange-800 hover:bg-orange-200" data-category="fire">
                    <i class="fas fa-fire-extinguisher mr-2"></i> Fire
                </button>
                <button class="filter-btn px-4 py-2 rounded-full bg-green-100 text-green-800 hover:bg-green-200" data-category="disaster">
                    <i class="fas fa-cloud-showers-heavy mr-2"></i> Disaster
                </button>
            </div>
        </div>
    </div>

    <main class="container max-w-full px-4 pb-16">
        <!-- Emergency Contacts Grid -->
        <section id="emergencyContacts">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">National Emergency Services</h2>
                <p class="text-gray-600">Immediate response services available 24/7 nationwide</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <!-- National Emergency Hotline -->
                <div class="emergency-card urgent-red p-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                            <img src="{{ asset('images/for_logo/911.jpeg') }}" alt="National Emergency Hotline" class="contact-logo">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold">National Emergency Hotline</h3>
                            <p class="opacity-90">Police, Fire, Ambulance, Rescue</p>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-4">911</div>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 opacity-80"></i>
                            <span>24/7 Service</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 opacity-80"></i>
                            <span>Nationwide Coverage</span>
                        </div>
                    </div>
                    <a href="tel:911" class="block w-full bg-white text-red-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-red-50 transition">
                        <i class="fas fa-phone mr-2"></i> Call Now
                    </a>
                </div>
                
                <!-- Emergency Patrol -->
                <div class="emergency-card police-dark p-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                            <img src="{{ asset('images/for_logo/pnp.png') }}" alt="Philippine National Police" class="contact-logo">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold">Philippine National Police (PNP)</h3>
                            <p class="opacity-90">Police Emergency</p>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-4">117</div>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 opacity-80"></i>
                            <span>24/7 Hotline</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-3 opacity-80"></i>
                            <span>Text: 0917-847-5757</span>
                        </div>
                    </div>
                    <a href="tel:117" class="block w-full bg-white text-gray-800 text-center font-bold py-3 px-4 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-phone mr-2"></i> Call Police
                    </a>
                </div>
                
                <!-- Bureau of Fire Protection -->
                <div class="emergency-card fire-orange p-6">
                    <div class="flex items-start mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                            <img src="{{ asset('images/for_logo/bfp.png') }}" alt="Bureau of Fire Protection" class="contact-logo">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold">Bureau of Fire Protection</h3>
                            <p class="opacity-90">Fire & Rescue Emergency</p>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-4">160</div>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-3 opacity-80"></i>
                            <span>24/7 Emergency</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-truck mr-3 opacity-80"></i>
                            <span>Fire & Rescue Response</span>
                        </div>
                    </div>
                    <a href="tel:160" class="block w-full bg-white text-orange-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-orange-50 transition">
                        <i class="fas fa-phone mr-2"></i> Call Fire Dept
                    </a>
                </div>
            </div>
            
            <!-- Medical & Hospitals Section -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-hospital text-blue-500 mr-2"></i> Medical & Hospital Services
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Red Cross -->
                    <div class="emergency-card hospital-blue p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/redcross.png') }}" alt="Philippine Red Cross" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">Philippine Red Cross</h3>
                                <p class="opacity-90">Medical Emergency & Ambulance</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold mb-4">143</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-ambulance mr-3 opacity-80"></i>
                                <span>24/7 Ambulance Service</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-heartbeat mr-3 opacity-80"></i>
                                <span>Blood Bank Services</span>
                            </div>
                        </div>
                        <a href="tel:143" class="block w-full bg-white text-blue-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-blue-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call Red Cross
                        </a>
                    </div>
                    
                    <!-- National Poison Control -->
                    <div class="emergency-card hospital-blue p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/posioncontrol.jpeg') }}" alt="National Poison Control" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">National Poison Control</h3>
                                <p class="opacity-90">Poison Information & Control</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold mb-4">(02) 8524-1078</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-3 opacity-80"></i>
                                <span>24/7 Poison Hotline</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-3 opacity-80"></i>
                                <span>Toxic Exposure Emergencies</span>
                            </div>
                        </div>
                        <a href="tel:0285241078" class="block w-full bg-white text-blue-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-blue-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call Poison Control
                        </a>
                    </div>
                    
                    <!-- DOH Health Emergency -->
                    <div class="emergency-card hospital-blue p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/doh.jpeg') }}" alt="DOH Health Emergency" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">DOH Health Emergency</h3>
                                <p class="opacity-90">Department of Health Hotline</p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold mb-4">1555</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-3 opacity-80"></i>
                                <span>24/7 Health Concerns</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-3 opacity-80"></i>
                                <span>Medical Information</span>
                            </div>
                        </div>
                        <a href="tel:1555" class="block w-full bg-white text-blue-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-blue-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call DOH
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Disaster Response Section -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-cloud-showers-heavy text-green-500 mr-2"></i> Disaster & Rescue Services
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- NDRRMC -->
                    <div class="emergency-card disaster-green p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/ndrrmc.jpeg') }}" alt="NDRRMC Operations" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">NDRRMC Operations</h3>
                                <p class="opacity-90">National Disaster Risk Reduction</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">(02) 8911-5061</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-water mr-3 opacity-80"></i>
                                <span>Flood & Typhoon Response</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-mountain mr-3 opacity-80"></i>
                                <span>Earthquake & Landslide</span>
                            </div>
                        </div>
                        <a href="tel:0289115061" class="block w-full bg-white text-green-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-green-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call NDRRMC
                        </a>
                    </div>
                    
                    <!-- Coast Guard -->
                    <div class="emergency-card disaster-green p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/coastguard.png') }}" alt="Philippine Coast Guard" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">Philippine Coast Guard</h3>
                                <p class="opacity-90">Maritime Search & Rescue</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">(02) 8527-8481</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-life-ring mr-3 opacity-80"></i>
                                <span>Marine Distress Calls</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-swimmer mr-3 opacity-80"></i>
                                <span>Water Rescue Operations</span>
                            </div>
                        </div>
                        <a href="tel:0285278481" class="block w-full bg-white text-green-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-green-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call Coast Guard
                        </a>
                    </div>
                    
                    <!-- MMDA -->
                    <div class="emergency-card disaster-green p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/mmda.jpeg') }}" alt="MMDA Road Emergency" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">MMDA Road Emergency</h3>
                                <p class="opacity-90">Metro Manila Road Emergencies</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">136</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-road mr-3 opacity-80"></i>
                                <span>Road Accident Response</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-traffic-light mr-3 opacity-80"></i>
                                <span>Traffic Management</span>
                            </div>
                        </div>
                        <a href="tel:136" class="block w-full bg-white text-green-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-green-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call MMDA
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Utility Services Section -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-tools text-purple-500 mr-2"></i> Utilities & Essential Services
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Meralco -->
                    <div class="emergency-card utility-purple p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/meralco.png') }}" alt="Meralco Power Emergency" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">Meralco Power Emergency</h3>
                                <p class="opacity-90">Electricity Emergency & Outages</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">16211</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-lightbulb mr-3 opacity-80"></i>
                                <span>Power Line Emergencies</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-3 opacity-80"></i>
                                <span>Electrical Hazard Reports</span>
                            </div>
                        </div>
                        <a href="tel:16211" class="block w-full bg-white text-purple-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-purple-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call Meralco
                        </a>
                    </div>
                    
                    <!-- Maynilad -->
                    <div class="emergency-card utility-purple p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/maynilad.jpeg') }}" alt="Maynilad Water Service" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">Maynilad Water Service</h3>
                                <p class="opacity-90">Water Line Emergencies</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">1626</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-water mr-3 opacity-80"></i>
                                <span>Water Main Breaks</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tint-slash mr-3 opacity-80"></i>
                                <span>No Water Supply</span>
                            </div>
                        </div>
                        <a href="tel:1626" class="block w-full bg-white text-purple-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-purple-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call Maynilad
                        </a>
                    </div>
                    
                    <!-- PLDT -->
                    <div class="emergency-card utility-purple p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/pldt.png') }}" alt="PLDT Landline Emergency" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">PLDT Landline Emergency</h3>
                                <p class="opacity-90">Telephone Line Problems</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">171</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-phone-slash mr-3 opacity-80"></i>
                                <span>Line Faults & Repairs</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-headset mr-3 opacity-80"></i>
                                <span>24/7 Customer Service</span>
                            </div>
                        </div>
                        <a href="tel:171" class="block w-full bg-white text-purple-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-purple-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call PLDT
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Government Agencies Section -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-landmark text-yellow-500 mr-2"></i> Government Agencies
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- DSWD -->
                    <div class="emergency-card govt-yellow p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/dswd.png') }}" alt="DSWD Crisis Assistance" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">DSWD Crisis Assistance</h3>
                                <p class="opacity-90">Social Welfare & Development</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">(02) 8931-8101</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-home mr-3 opacity-80"></i>
                                <span>Disaster Relief Assistance</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-friends mr-3 opacity-80"></i>
                                <span>Family Crisis Support</span>
                            </div>
                        </div>
                        <a href="tel:0289318101" class="block w-full bg-white text-yellow-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-yellow-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call DSWD
                        </a>
                    </div>
                    
                    <!-- DTI Consumer Hotline -->
                    <div class="emergency-card govt-yellow p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/dti.png') }}" alt="DTI Consumer Complaints" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">DTI Consumer Complaints</h3>
                                <p class="opacity-90">Trade & Industry Concerns</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">(02) 8751-3330</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-3 opacity-80"></i>
                                <span>Consumer Protection</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-balance-scale mr-3 opacity-80"></i>
                                <span>Business Complaints</span>
                            </div>
                        </div>
                        <a href="tel:0287513330" class="block w-full bg-white text-yellow-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-yellow-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call DTI
                        </a>
                    </div>
                    
                    <!-- Civil Aviation -->
                    <div class="emergency-card govt-yellow p-6">
                        <div class="flex items-start mb-4">
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                                <img src="{{ asset('images/for_logo/caa.jpeg') }}" alt="Civil Aviation Authority" class="contact-logo">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">Civil Aviation Authority</h3>
                                <p class="opacity-90">Aviation Emergencies</p>
                            </div>
                        </div>
                        <div class="text-xl font-bold mb-4">(02) 8854-5996</div>
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-plane-arrival mr-3 opacity-80"></i>
                                <span>Airport Emergencies</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-3 opacity-80"></i>
                                <span>Aviation Safety Reports</span>
                            </div>
                        </div>
                        <a href="tel:0288545996" class="block w-full bg-white text-yellow-600 text-center font-bold py-3 px-4 rounded-lg hover:bg-yellow-50 transition">
                            <i class="fas fa-phone mr-2"></i> Call CAAP
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Regional Emergency Contacts -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Regional Emergency Contacts
                </h2>
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region/Province</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Police</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fire</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">National Capital Region (NCR)</td>
                                    <td class="px-6 py-4 whitespace-nowrap">117 / 911</td>
                                    <td class="px-6 py-4 whitespace-nowrap">160 / 911</td>
                                    <td class="px-6 py-4 whitespace-nowrap">143 / 911</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Region III (Central Luzon)</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(045) 455-3153</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(045) 455-3154</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(045) 455-3155</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Region IV-A (CALABARZON)</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(049) 545-6789</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(049) 545-6790</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(049) 545-6791</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Region VII (Central Visayas)</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(032) 254-7890</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(032) 254-7891</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(032) 254-7892</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Region XI (Davao Region)</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(082) 221-4567</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(082) 221-4568</td>
                                    <td class="px-6 py-4 whitespace-nowrap">(082) 221-4569</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Emergency Preparedness Tips -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Emergency Preparedness Tips</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i> Before an Emergency
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Save emergency contacts in your phone</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Prepare an emergency kit with essentials</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Know the evacuation routes in your area</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Keep important documents in waterproof container</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> During an Emergency
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Stay calm and assess the situation</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Call the appropriate emergency number</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Follow instructions from authorities</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <span>Help others if it's safe to do so</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </main>


    <!-- Quick Call Modal -->
    <div id="quickCallModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-phone-alt text-red-600 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Call Emergency Number</h2>
            <p class="text-gray-600 mb-6">You are about to call:</p>
            <div class="text-3xl font-bold text-red-600 mb-6" id="modalNumber">911</div>
            <div class="flex gap-4">
                <button onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-xl">
                    Cancel
                </button>
                <a id="callLink" href="tel:911" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl flex items-center justify-center">
                    <i class="fas fa-phone mr-2"></i> Call Now
                </a>
            </div>
        </div>
    </div>

    <script>
        // Emergency contacts data
        const emergencyContacts = [
            // National Emergencies
            { id: 1, name: "National Emergency Hotline", number: "911", category: "urgent", type: "All Emergencies", description: "Police, Fire, Ambulance, Rescue", color: "red" },
            { id: 2, name: "Philippine National Police (PNP)", number: "117", category: "police", type: "Police Emergency", description: "Police Hotline", color: "dark" },
            { id: 3, name: "Bureau of Fire Protection", number: "160", category: "fire", type: "Fire & Rescue", description: "Fire Emergency", color: "orange" },
            
            // Medical Services
            { id: 4, name: "Philippine Red Cross", number: "143", category: "medical", type: "Medical Emergency", description: "Ambulance & Blood Bank", color: "blue" },
            { id: 5, name: "National Poison Control", number: "(02) 8524-1078", category: "medical", type: "Poison Control", description: "Poison Information", color: "blue" },
            { id: 6, name: "DOH Health Emergency", number: "1555", category: "medical", type: "Health Concerns", description: "Medical Information", color: "blue" },
            
            // Disaster Response
            { id: 7, name: "NDRRMC Operations", number: "(02) 8911-5061", category: "disaster", type: "Disaster Response", description: "Flood, Typhoon, Earthquake", color: "green" },
            { id: 8, name: "Philippine Coast Guard", number: "(02) 8527-8481", category: "disaster", type: "Maritime Rescue", description: "Water & Sea Rescue", color: "green" },
            { id: 9, name: "MMDA Road Emergency", number: "136", category: "disaster", type: "Road Emergency", description: "Traffic & Road Accidents", color: "green" },
            
            // Utilities
            { id: 10, name: "Meralco Power Emergency", number: "16211", category: "utility", type: "Electricity", description: "Power Outages & Emergencies", color: "purple" },
            { id: 11, name: "Maynilad Water Service", number: "1626", category: "utility", type: "Water Service", description: "Water Line Emergencies", color: "purple" },
            { id: 12, name: "PLDT Landline Emergency", number: "171", category: "utility", type: "Telephone", description: "Line Faults & Repairs", color: "purple" },
            
            // Government
            { id: 13, name: "DSWD Crisis Assistance", number: "(02) 8931-8101", category: "government", type: "Social Welfare", description: "Disaster Relief & Support", color: "yellow" },
            { id: 14, name: "DTI Consumer Complaints", number: "(02) 8751-3330", category: "government", type: "Consumer Protection", description: "Business Complaints", color: "yellow" },
            { id: 15, name: "Civil Aviation Authority", number: "(02) 8854-5996", category: "government", type: "Aviation", description: "Airport Emergencies", color: "yellow" },
        ];
        
        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const emergencyContactsSection = document.getElementById('emergencyContacts');
        const quickCallModal = document.getElementById('quickCallModal');
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            highlightImportantNumbers();
        });
        
        function setupEventListeners() {
            // Search functionality
            searchInput.addEventListener('input', function(e) {
                filterContacts();
            });
            
            // Category filter
            categoryFilter.addEventListener('change', function() {
                filterContacts();
            });
            
            // Quick filter buttons
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Set category filter
                    const category = this.dataset.category;
                    categoryFilter.value = category === 'urgent' ? 'all' : category;
                    
                    // Trigger filter
                    filterContacts();
                });
            });
            
            // Close modal when clicking outside
            quickCallModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
            
            // Close modal with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
            
            // Add click event to all call buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.emergency-card a[href^="tel:"]')) {
                    const link = e.target.closest('a');
                    const number = link.getAttribute('href').replace('tel:', '');
                    const card = link.closest('.emergency-card');
                    const name = card.querySelector('h3').textContent;
                    
                    // For 911 and other major numbers, show confirmation
                    if (['911', '117', '160', '143'].includes(number)) {
                        e.preventDefault();
                        showCallModal(number, name);
                    }
                }
            });
        }
        
        function filterContacts() {
            const searchTerm = searchInput.value.toLowerCase();
            const category = categoryFilter.value;
            
            // Get all emergency cards (excluding the ones in tables)
            const cards = document.querySelectorAll('.emergency-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                const cardCategory = card.classList.contains('urgent-red') ? 'urgent' :
                                   card.classList.contains('hospital-blue') ? 'medical' :
                                   card.classList.contains('fire-orange') ? 'fire' :
                                   card.classList.contains('police-dark') ? 'police' :
                                   card.classList.contains('disaster-green') ? 'disaster' :
                                   card.classList.contains('utility-purple') ? 'utility' :
                                   card.classList.contains('govt-yellow') ? 'government' : '';
                
                const matchesSearch = searchTerm === '' || cardText.includes(searchTerm);
                const matchesCategory = category === 'all' || cardCategory === category;
                
                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                    visibleCount++;
                    
                    // Highlight search term in text
                    if (searchTerm) {
                        highlightText(card, searchTerm);
                    }
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show message if no results
            showNoResultsMessage(visibleCount === 0);
        }
        
        function highlightText(element, searchTerm) {
            const walker = document.createTreeWalker(
                element,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );
            
            let node;
            const nodes = [];
            while (node = walker.nextNode()) {
                if (node.textContent.toLowerCase().includes(searchTerm)) {
                    nodes.push(node);
                }
            }
            
            nodes.forEach(node => {
                const span = document.createElement('span');
                span.innerHTML = node.textContent.replace(
                    new RegExp(searchTerm, 'gi'),
                    match => `<span class="search-highlight">${match}</span>`
                );
                node.parentNode.replaceChild(span, node);
            });
        }
        
        function showNoResultsMessage(show) {
            let message = document.getElementById('noResultsMessage');
            
            if (show && !message) {
                message = document.createElement('div');
                message.id = 'noResultsMessage';
                message.className = 'col-span-full text-center py-12';
                message.innerHTML = `
                    <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No emergency contacts found</h3>
                    <p class="text-gray-500">Try a different search term or category</p>
                `;
                
                // Insert after the search section
                emergencyContactsSection.appendChild(message);
            } else if (!show && message) {
                message.remove();
            }
        }
        
        function showCallModal(number, name) {
            document.getElementById('modalNumber').textContent = number;
            document.getElementById('callLink').href = `tel:${number}`;
            quickCallModal.classList.remove('hidden');
            
            // Update modal title with service name
            document.querySelector('#quickCallModal h2').textContent = `Call ${name}`;
        }
        
        function closeModal() {
            quickCallModal.classList.add('hidden');
        }
        
        function highlightImportantNumbers() {
            // Add visual emphasis to critical numbers
            const criticalNumbers = ['911', '117', '160', '143'];
            
            document.querySelectorAll('.emergency-card').forEach(card => {
                const numberElement = card.querySelector('.text-3xl, .text-2xl');
                if (numberElement) {
                    const number = numberElement.textContent.trim();
                    if (criticalNumbers.includes(number)) {
                        card.classList.add('ring-4', 'ring-opacity-30', 'ring-yellow-400');
                    }
                }
            });
        }
        
        function saveContacts() {
            // Create a downloadable text file with all contacts
            let content = "PHILIPPINES EMERGENCY CONTACTS LIST\n";
            content += "=====================================\n\n";
            
            emergencyContacts.forEach(contact => {
                content += `${contact.name}\n`;
                content += `Number: ${contact.number}\n`;
                content += `Type: ${contact.type}\n`;
                content += `Description: ${contact.description}\n`;
                content += "-\n";
            });
            
            content += "\nIMPORTANT NUMBERS TO REMEMBER:\n";
            content += "911 - National Emergency Hotline\n";
            content += "117 - Philippine National Police\n";
            content += "160 - Bureau of Fire Protection\n";
            content += "143 - Philippine Red Cross\n\n";
            
            content += "Generated on: " + new Date().toLocaleDateString() + "\n";
            content += "Keep this list accessible at all times.\n";
            
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'Philippines-Emergency-Contacts.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            
            // Show success message
            showNotification('Emergency contacts list downloaded successfully!');
        }
        
        function printContacts() {
            // Open print dialog
            window.print();
        }
        
        function shareContacts() {
            if (navigator.share) {
                navigator.share({
                    title: 'Philippines Emergency Contacts',
                    text: 'Complete list of emergency numbers for the Philippines. Save this important information!',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const shareText = `Philippines Emergency Contacts:\n\nMain Numbers:\n911 - National Emergency\n117 - Police\n160 - Fire\n143 - Red Cross\n\nFull list: ${window.location.href}`;
                navigator.clipboard.writeText(shareText).then(() => {
                    showNotification('Emergency contacts copied to clipboard!');
                });
            }
        }
        
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
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
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fade-out {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(10px); }
            }
            .animate-fade-in { animation: fade-in 0.3s ease-out; }
            .animate-fade-out { animation: fade-out 0.3s ease-out; }
        `;
        document.head.appendChild(style);
    </script>
@endsection
