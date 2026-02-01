@extends('layouts.plain')
@section('title', 'Report an Incident')
@section('content')
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4 {
            font-family: 'Poppins', sans-serif;
        }
        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .step-active {
            background-color: #3B82F6;
            color: white;
        }
        .step-inactive {
            background-color: #E5E7EB;
            color: #6B7280;
        }
        .image-preview {
            transition: all 0.3s ease;
        }
        .image-preview:hover {
            transform: scale(1.05);
        }
        .incident-type-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .incident-type-card:hover {
            transform: translateY(-5px);
        }
        .incident-type-selected {
            border: 3px solid #3B82F6;
            background-color: #EFF6FF;
        }
        /* Larger form elements for better accessibility */
        .form-input {
            font-size: 1.1rem;
            padding: 14px;
        }
        .form-label {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        /* High contrast for better readability */
        .high-contrast {
            background-color: #F9FAFB;
            border: 2px solid #E5E7EB;
        }
        /* Custom notification styles */
        .custom-notification {
            min-width: 300px;
            max-width: 400px;
            z-index: 9999;
        }
        @media (max-width: 640px) {
            .custom-notification {
                min-width: auto;
                width: calc(100% - 2rem);
                left: 1rem;
                right: 1rem;
            }
        }
        /* Loading spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center">
            <div class="w-16 h-16 mx-auto mb-6">
                <div class="spinner rounded-full h-full w-full border-b-2 border-blue-600"></div>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Submitting Report</h3>
            <p class="text-gray-600">Please wait while we process your report...</p>
        </div>
    </div>

    <!-- Simple Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center">
                <a href="{{route('landing')}}" class="mr-4 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left text-2xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Report an Incident</h1>
                    <p class="text-gray-600">Help us keep our community safe</p>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Progress Steps -->
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-center mb-8">
            <div class="flex items-center">
                <!-- Step 1 -->
                <div class="flex flex-col items-center">
                    <div class="step-circle step-active">
                        1
                    </div>
                    <p class="mt-2 font-medium text-blue-600">Type</p>
                </div>
                <div class="w-16 h-1 bg-blue-600 mx-2"></div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center">
                    <div class="step-circle step-inactive">
                        2
                    </div>
                    <p class="mt-2 font-medium text-gray-500">Details</p>
                </div>
                <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                
                <!-- Step 3 -->
                <div class="flex flex-col items-center">
                    <div class="step-circle step-inactive">
                        3
                    </div>
                    <p class="mt-2 font-medium text-gray-500">Photos</p>
                </div>
                <div class="w-16 h-1 bg-gray-300 mx-2"></div>
                
                <!-- Step 4 -->
                <div class="flex flex-col items-center">
                    <div class="step-circle step-inactive">
                        4
                    </div>
                    <p class="mt-2 font-medium text-gray-500">Submit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <main class="container mx-auto px-4 pb-16">
        <form id="incidentReportForm"  enctype="multipart/form-data">
            @csrf
            <div class="max-w-4xl mx-auto">
                <!-- Hidden inputs for form data -->
                <input type="hidden" name="incident_type" id="incidentTypeInput">
                <input type="hidden" name="latitude" id="latitudeInput">
                <input type="hidden" name="longitude" id="longitudeInput">
                <input type="hidden" name="urgency_level" id="urgencyLevelInput">

                <!-- Step 1: Incident Type Selection -->
                <div id="step1" class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">What type of incident is this?</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <!-- Natural Disaster -->
                        <div class="incident-type-card high-contrast rounded-xl p-5" data-type="natural_disaster">
                            <div class="flex items-start">
                                <div class="bg-red-100 p-3 rounded-lg mr-4">
                                    <i class="fas fa-cloud-showers-heavy text-red-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Natural Disaster</h3>
                                    <p class="text-gray-600">Flood, storm, earthquake, landslide, fire</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Accident -->
                        <div class="incident-type-card high-contrast rounded-xl p-5" data-type="accident">
                            <div class="flex items-start">
                                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                                    <i class="fas fa-car-crash text-yellow-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Accident</h3>
                                    <p class="text-gray-600">Vehicle accident, fall, injury</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Crime / Security -->
                        <div class="incident-type-card high-contrast rounded-xl p-5" data-type="crime_security">
                            <div class="flex items-start">
                                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                    <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Crime / Security</h3>
                                    <p class="text-gray-600">Theft, fight, suspicious activity</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Infrastructure Problem -->
                        <div class="incident-type-card high-contrast rounded-xl p-5" data-type="infrastructure">
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                    <i class="fas fa-road text-blue-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Infrastructure Problem</h3>
                                    <p class="text-gray-600">Road damage, broken street light, water leak</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Health Emergency -->
                        <div class="incident-type-card high-contrast rounded-xl p-5" data-type="health_emergency">
                            <div class="flex items-start">
                                <div class="bg-green-100 p-3 rounded-lg mr-4">
                                    <i class="fas fa-heartbeat text-green-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Health Emergency</h3>
                                    <p class="text-gray-600">Medical emergency, sick person needs help</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Other -->
                        <div class="incident-type-card high-contrast rounded-xl p-5" data-type="other">
                            <div class="flex items-start">
                                <div class="bg-gray-100 p-3 rounded-lg mr-4">
                                    <i class="fas fa-question-circle text-gray-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Other Problem</h3>
                                    <p class="text-gray-600">Something else that needs attention</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" id="nextToStep2" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-lg">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Incident Details -->
                <div id="step2" class="bg-white rounded-2xl shadow-lg p-6 mb-6 hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tell us what happened</h2>
                    
                    <div class="space-y-6">
                        <!-- Location -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Where did this happen?
                            </label>
                            <input type="text" name="location" id="locationInput" placeholder="Example: Near the market, Main Street, Barangay Hall" class="w-full form-input high-contrast rounded-xl" required>
                            <button type="button" id="useCurrentLocation" class="mt-2 text-blue-600 font-medium hover:text-blue-800 transition-colors">
                                <i class="fas fa-location-dot mr-1"></i> Use my current location
                            </button>
                        </div>
                        
                        <!-- Date and Time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">
                                    <i class="far fa-calendar mr-2 text-blue-600"></i>Date
                                </label>
                                <input type="date" name="incident_date" id="incidentDateInput" class="w-full form-input high-contrast rounded-xl" required>
                            </div>
                            <div>
                                <label class="form-label">
                                    <i class="far fa-clock mr-2 text-blue-600"></i>Time
                                </label>
                                <input type="time" name="incident_time" id="incidentTimeInput" class="w-full form-input high-contrast rounded-xl" required>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-align-left mr-2 text-blue-600"></i>Describe what happened
                            </label>
                            <textarea name="description" id="descriptionInput" rows="5" placeholder="Please describe the incident in your own words. What did you see? What happened? Who was involved?" class="w-full form-input high-contrast rounded-xl" required></textarea>
                            <p class="text-gray-500 text-sm mt-2">Tell us as much as you can. This helps us respond better.</p>
                        </div>
                        
                        <!-- Urgency Level -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-exclamation-circle mr-2 text-blue-600"></i>How urgent is this?
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <button type="button" class="urgency-btn py-4 rounded-xl border-2 border-gray-300 hover:border-green-500 bg-white transition-all" data-level="low">
                                    <i class="fas fa-walking text-2xl text-green-600 mb-2"></i>
                                    <p class="font-bold text-gray-800">Not Urgent</p>
                                    <p class="text-gray-600 text-sm">Can wait a few days</p>
                                </button>
                                <button type="button" class="urgency-btn py-4 rounded-xl border-2 border-gray-300 hover:border-yellow-500 bg-white transition-all" data-level="medium">
                                    <i class="fas fa-running text-2xl text-yellow-600 mb-2"></i>
                                    <p class="font-bold text-gray-800">Somewhat Urgent</p>
                                    <p class="text-gray-600 text-sm">Needs attention today</p>
                                </button>
                                <button type="button" class="urgency-btn py-4 rounded-xl border-2 border-gray-300 hover:border-red-500 bg-white transition-all" data-level="high">
                                    <i class="fas fa-ambulance text-2xl text-red-600 mb-2"></i>
                                    <p class="font-bold text-gray-800">Very Urgent</p>
                                    <p class="text-gray-600 text-sm">Needs help right now</p>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-8">
                        <button type="button" id="backToStep1" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" id="nextToStep3" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Photo Upload -->
                <div id="step3" class="bg-white rounded-2xl shadow-lg p-6 mb-6 hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add photos (if you have any)</h2>
                    <p class="text-gray-600 mb-6">Photos help us understand the situation better. You can upload 1-5 photos.</p>
                    
                    <!-- Photo Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center mb-6 high-contrast transition-colors" id="uploadArea">
                        <i class="fas fa-cloud-upload-alt text-blue-500 text-5xl mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Add photos here</h3>
                        <p class="text-gray-600 mb-4">Drag and drop photos, or click to browse</p>
                        <input type="file" name="photos[]" id="photoUpload" accept="image/*" multiple class="hidden">
                        <button type="button" id="browsePhotos" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            <i class="fas fa-camera mr-2"></i> Choose Photos
                        </button>
                        <p class="text-gray-500 text-sm mt-4">You can upload up to 5 photos. Each photo should be less than 5MB.</p>
                    </div>
                    
                    <!-- Photo Preview Area -->
                    <div id="photoPreview" class="mb-6 hidden">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Your photos (<span id="photoCount">0</span>/5)</h3>
                        <div id="previewContainer" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <!-- Photos will appear here -->
                        </div>
                    </div>
                    
                    <!-- Photo Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
                        <h3 class="text-lg font-bold text-blue-800 mb-3"><i class="fas fa-lightbulb mr-2"></i> Photo Tips</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <p>Take clear photos of the problem area</p>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <p>Include landmarks to show location</p>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                <p>Photos help us respond faster</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" id="backToStep2" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" id="nextToStep4" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Review and Submit -->
                <div id="step4" class="bg-white rounded-2xl shadow-lg p-6 mb-6 hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Review and submit your report</h2>
                    
                    <!-- Summary -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Your Report Summary</h3>
                        
                        <div class="space-y-4">
                            <div class="flex">
                                <div class="w-1/3 font-bold text-gray-700">Incident Type:</div>
                                <div id="summaryType" class="w-2/3 text-gray-800">-</div>
                            </div>
                            <div class="flex">
                                <div class="w-1/3 font-bold text-gray-700">Location:</div>
                                <div id="summaryLocation" class="w-2/3 text-gray-800">-</div>
                            </div>
                            <div class="flex">
                                <div class="w-1/3 font-bold text-gray-700">Date & Time:</div>
                                <div id="summaryDateTime" class="w-2/3 text-gray-800">-</div>
                            </div>
                            <div class="flex">
                                <div class="w-1/3 font-bold text-gray-700">Urgency:</div>
                                <div id="summaryUrgency" class="w-2/3 text-gray-800">-</div>
                            </div>
                            <div class="flex">
                                <div class="w-1/3 font-bold text-gray-700">Photos:</div>
                                <div id="summaryPhotos" class="w-2/3 text-gray-800">0 photos</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information (Optional) -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Your Contact Information (Optional)</h3>
                        <p class="text-gray-600 mb-4">This helps us contact you if we need more information.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Your Name</label>
                                <input type="text" name="reporter_name" id="userNameInput" placeholder="Optional" class="w-full form-input high-contrast rounded-xl">
                            </div>
                            <div>
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="reporter_phone" id="userPhoneInput" placeholder="Optional" class="w-full form-input high-contrast rounded-xl">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Privacy Note -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
                        <h3 class="text-lg font-bold text-yellow-800 mb-2"><i class="fas fa-lock mr-2"></i> Your Privacy</h3>
                        <p class="text-yellow-800">Your information is safe with us. We only use it to follow up on this report. You can report anonymously if you prefer.</p>
                    </div>
                    
                    <div class="flex justify-between">
                        <button type="button" id="backToStep3" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" id="submitReport" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Report
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Report Submitted!</h2>
            <p class="text-gray-600 mb-6">Thank you for helping keep our community safe. We've received your report and will review it soon.</p>
            <div class="mb-6">
                <div class="bg-blue-50 p-4 rounded-xl">
                    <p class="font-bold text-blue-800">Your Report ID:</p>
                    <p class="text-2xl font-bold text-blue-600" id="reportIdDisplay">REP-0000</p>
                    <p class="text-blue-700 text-sm mt-2">Save this number for reference</p>
                </div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('landing') }}" class="block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <button type="button" id="newReport" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-xl transition-colors">
                    <i class="fas fa-plus mr-2"></i> Report Another Incident
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-xl font-bold mb-4">Need Immediate Help?</h3>
            <div class="bg-red-600 text-white text-2xl font-bold px-6 py-3 rounded-xl inline-block mb-4">
                <i class="fas fa-phone mr-2"></i> Call 911
            </div>
            <p class="text-gray-300">For emergencies requiring police, fire, or medical assistance</p>
        </div>
    </footer>

    <script>
        // Report data storage
        let reportData = {
            type: '',
            location: '',
            date: '',
            time: '',
            description: '',
            urgency: '',
            photos: [],
            userName: '',
            userPhone: '',
            latitude: '',
            longitude: ''
        };
        
        // Step navigation
        const steps = document.querySelectorAll('[id^="step"]');
        const stepCircles = document.querySelectorAll('.step-circle');
        
        // Navigation functions
        function goToStep(stepNumber) {
            // Hide all steps
            steps.forEach(step => {
                step.classList.add('hidden');
            });
            
            // Show current step
            document.getElementById(`step${stepNumber}`).classList.remove('hidden');
            
            // Update progress circles
            stepCircles.forEach((circle, index) => {
                if (index < stepNumber) {
                    circle.classList.remove('step-inactive');
                    circle.classList.add('step-active');
                } else {
                    circle.classList.remove('step-active');
                    circle.classList.add('step-inactive');
                }
            });
        }
        
        // Step 1: Incident Type Selection
        const incidentTypeCards = document.querySelectorAll('.incident-type-card');
        incidentTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove selection from all cards
                incidentTypeCards.forEach(c => {
                    c.classList.remove('incident-type-selected');
                });
                
                // Add selection to clicked card
                this.classList.add('incident-type-selected');
                
                // Store selected type
                const type = this.dataset.type;
                reportData.type = type;
                document.getElementById('incidentTypeInput').value = type;
            });
        });
        
        document.getElementById('nextToStep2').addEventListener('click', function() {
            if (!reportData.type) {
                showNotification('Please select an incident type first.', 'error');
                return;
            }
            goToStep(2);
        });
        
        // Step 2: Incident Details
        document.getElementById('backToStep1').addEventListener('click', function() {
            goToStep(1);
        });
        
        // Use current location
        document.getElementById('useCurrentLocation').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Getting location...';
            button.disabled = true;
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Update location input
                        const locationInput = document.getElementById('locationInput');
                        locationInput.value = `Latitude: ${lat.toFixed(6)}, Longitude: ${lng.toFixed(6)}`;
                        
                        // Store coordinates in hidden inputs
                        document.getElementById('latitudeInput').value = lat;
                        document.getElementById('longitudeInput').value = lng;
                        
                        // Update reportData
                        reportData.latitude = lat;
                        reportData.longitude = lng;
                        reportData.location = locationInput.value;
                        
                        button.innerHTML = originalText;
                        button.disabled = false;
                        
                        showNotification('Location retrieved successfully!', 'success');
                    },
                    function(error) {
                        let errorMessage = 'Unable to get your location. Please enter it manually.';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Location permission denied. Please enable location services in your browser.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Location information unavailable.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "Location request timed out.";
                                break;
                        }
                        
                        showNotification(errorMessage, 'error');
                        button.innerHTML = originalText;
                        button.disabled = false;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                showNotification('Geolocation is not supported by your browser.', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
        
        // Urgency selection
        const urgencyButtons = document.querySelectorAll('.urgency-btn');
        let selectedUrgencyButton = null;
        
        urgencyButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove selection from all buttons
                urgencyButtons.forEach(btn => {
                    btn.classList.remove('border-green-500', 'border-yellow-500', 'border-red-500');
                    btn.classList.add('border-gray-300');
                });
                
                // Add selection to clicked button
                const level = this.dataset.level;
                const colorClass = {
                    'low': 'border-green-500',
                    'medium': 'border-yellow-500',
                    'high': 'border-red-500'
                }[level];
                
                this.classList.remove('border-gray-300');
                this.classList.add(colorClass);
                selectedUrgencyButton = this;
                
                // Store selected urgency
                reportData.urgency = level;
                document.getElementById('urgencyLevelInput').value = level;
            });
        });
        
        document.getElementById('nextToStep3').addEventListener('click', function() {
            // Get values from inputs
            const location = document.getElementById('locationInput').value;
            const date = document.getElementById('incidentDateInput').value;
            const time = document.getElementById('incidentTimeInput').value;
            const description = document.getElementById('descriptionInput').value;
            
            // Validation
            const errors = [];
            
            if (!location.trim()) {
                errors.push('Please enter a location.');
                document.getElementById('locationInput').classList.add('border-red-500');
            } else {
                document.getElementById('locationInput').classList.remove('border-red-500');
            }
            
            if (!date) {
                errors.push('Please enter the date.');
                document.getElementById('incidentDateInput').classList.add('border-red-500');
            } else {
                document.getElementById('incidentDateInput').classList.remove('border-red-500');
            }
            
            if (!time) {
                errors.push('Please enter the time.');
                document.getElementById('incidentTimeInput').classList.add('border-red-500');
            } else {
                document.getElementById('incidentTimeInput').classList.remove('border-red-500');
            }
            
            if (!description.trim()) {
                errors.push('Please describe what happened.');
                document.getElementById('descriptionInput').classList.add('border-red-500');
            } else {
                document.getElementById('descriptionInput').classList.remove('border-red-500');
            }
            
            if (!reportData.urgency) {
                errors.push('Please select how urgent this is.');
            }
            
            if (errors.length > 0) {
                showNotification(errors.join('<br>'), 'error');
                return;
            }
            
            // Store data
            reportData.location = location;
            reportData.date = date;
            reportData.time = time;
            reportData.description = description;
            
            goToStep(3);
        });
        
        // Step 3: Photo Upload
        document.getElementById('backToStep2').addEventListener('click', function() {
            goToStep(2);
        });
        
        // Photo upload functionality
        const photoUpload = document.getElementById('photoUpload');
        const browsePhotos = document.getElementById('browsePhotos');
        const previewContainer = document.getElementById('previewContainer');
        const photoCount = document.getElementById('photoCount');
        const uploadArea = document.getElementById('uploadArea');
        
        browsePhotos.addEventListener('click', function() {
            photoUpload.click();
        });
        
        // Drag and drop functionality
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-500', 'bg-blue-50');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
            
            if (e.dataTransfer.files.length) {
                handleFiles(e.dataTransfer.files);
            }
        });
        
        photoUpload.addEventListener('change', function(event) {
            if (event.target.files.length) {
                handleFiles(event.target.files);
            }
        });
        
        function handleFiles(files) {
            const fileArray = Array.from(files);
            
            // Check if total photos exceed 5
            if (reportData.photos.length + fileArray.length > 5) {
                showNotification('You can only upload up to 5 photos total.', 'error');
                return;
            }
            
            fileArray.forEach(file => {
                if (!file.type.startsWith('image/')) {
                    showNotification(`${file.name} is not an image file. Please select image files only.`, 'error');
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    showNotification(`${file.name} is too large. Please select files under 5MB.`, 'error');
                    return;
                }
                
                // Add to reportData
                reportData.photos.push(file);
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'image-preview relative rounded-xl overflow-hidden group h-40';
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                            <button type="button" class="remove-photo transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                    previewContainer.appendChild(preview);
                    
                    // Add remove functionality
                    preview.querySelector('.remove-photo').addEventListener('click', function() {
                        const index = Array.from(previewContainer.children).indexOf(preview);
                        reportData.photos.splice(index, 1);
                        preview.remove();
                        updatePhotoCount();
                        showNotification('Photo removed', 'info');
                    });
                    
                    updatePhotoCount();
                };
                reader.readAsDataURL(file);
            });
            
            // Reset file input
            photoUpload.value = '';
        }
        
        function updatePhotoCount() {
            const count = reportData.photos.length;
            photoCount.textContent = count;
            
            // Show/hide preview area
            const photoPreview = document.getElementById('photoPreview');
            if (count > 0) {
                photoPreview.classList.remove('hidden');
            } else {
                photoPreview.classList.add('hidden');
            }
        }
        
        document.getElementById('nextToStep4').addEventListener('click', function() {
            // Update summary before going to step 4
            updateSummary();
            goToStep(4);
        });
        
        // Step 4: Review and Submit
        document.getElementById('backToStep3').addEventListener('click', function() {
            goToStep(3);
        });
        
        function updateSummary() {
            // Type
            const typeMap = {
                'natural_disaster': 'Natural Disaster',
                'accident': 'Accident',
                'crime_security': 'Crime / Security',
                'infrastructure': 'Infrastructure Problem',
                'health_emergency': 'Health Emergency',
                'other': 'Other Problem'
            };
            document.getElementById('summaryType').textContent = typeMap[reportData.type] || '-';
            
            // Location
            document.getElementById('summaryLocation').textContent = reportData.location || '-';
            
            // Date & Time
            if (reportData.date && reportData.time) {
                const dateObj = new Date(reportData.date);
                const formattedDate = dateObj.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                const timeParts = reportData.time.split(':');
                const hours = parseInt(timeParts[0]);
                const minutes = timeParts[1];
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const formattedHours = hours % 12 || 12;
                const formattedTime = `${formattedHours}:${minutes} ${ampm}`;
                
                document.getElementById('summaryDateTime').textContent = `${formattedDate} at ${formattedTime}`;
            } else {
                document.getElementById('summaryDateTime').textContent = '-';
            }
            
            // Urgency
            const urgencyMap = {
                'low': 'Not Urgent',
                'medium': 'Somewhat Urgent',
                'high': 'Very Urgent'
            };
            document.getElementById('summaryUrgency').textContent = urgencyMap[reportData.urgency] || '-';
            
            // Photos
            const photoCount = reportData.photos.length;
            document.getElementById('summaryPhotos').textContent = `${photoCount} photo${photoCount !== 1 ? 's' : ''}`;
        }
        
        // Form submission
        document.getElementById('submitReport').addEventListener('click', async function() {
            const submitButton = this;
            const originalText = submitButton.innerHTML;
            
            // Show loading overlay
            document.getElementById('loadingOverlay').classList.remove('hidden');
            
            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
            
            try {
                // Get optional contact info
                reportData.userName = document.getElementById('userNameInput').value;
                reportData.userPhone = document.getElementById('userPhoneInput').value;
                
                // Create FormData object
                const formData = new FormData();
                
                // Add form fields
                formData.append('incident_type', reportData.type);
                formData.append('location', reportData.location);
                formData.append('latitude', reportData.latitude || '');
                formData.append('longitude', reportData.longitude || '');
                formData.append('incident_date', reportData.date);
                formData.append('incident_time', reportData.time);
                formData.append('description', reportData.description);
                formData.append('urgency_level', reportData.urgency);
                formData.append('reporter_name', reportData.userName);
                formData.append('reporter_phone', reportData.userPhone);
                
                // Add CSRF token
                const csrfToken = document.querySelector('input[name="_token"]').value;
                formData.append('_token', csrfToken);
                
                // Add photos
                reportData.photos.forEach((photo, index) => {
                    formData.append(`photos[${index}]`, photo);
                });
                
                // Submit via AJAX (use relative URL to avoid mixed-content issues on HTTPS)
                const response = await fetch('{{ route("reports.store", [], false) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                // Hide loading overlay
                document.getElementById('loadingOverlay').classList.add('hidden');
                
                if (response.ok && result.success) {
                    // Update report ID display
                    const reportId = result.data.report_id || `REP-${Date.now().toString().slice(-8)}`;
                    document.getElementById('reportIdDisplay').textContent = reportId;
                    
                    // Show success modal
                    document.getElementById('successModal').classList.remove('hidden');
                    
                    console.log('Report submitted successfully:', result.data);
                } else {
                    // Handle validation errors
                    if (result.errors) {
                        let errorMessages = [];
                        Object.values(result.errors).forEach(error => {
                            errorMessages.push(...error);
                        });
                        showNotification(errorMessages.join('<br>'), 'error');
                    } else {
                        showNotification(result.message || 'Failed to submit report. Please try again.', 'error');
                    }
                    
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
                
            } catch (error) {
                console.error('Submission error:', error);
                
                // Hide loading overlay
                document.getElementById('loadingOverlay').classList.add('hidden');
                
                showNotification('Network error. Please check your connection and try again.', 'error');
                
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
        
        // Success modal buttons
        document.getElementById('newReport').addEventListener('click', function() {
            // Reset form
            resetForm();
            
            // Hide modal and go to step 1
            document.getElementById('successModal').classList.add('hidden');
            goToStep(1);
        });
        
        // Close modal when clicking outside
        document.getElementById('successModal').addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Helper function to reset form
        function resetForm() {
            // Reset reportData
            reportData = {
                type: '',
                location: '',
                date: '',
                time: '',
                description: '',
                urgency: '',
                photos: [],
                userName: '',
                userPhone: '',
                latitude: '',
                longitude: ''
            };
            
            // Reset UI
            incidentTypeCards.forEach(card => card.classList.remove('incident-type-selected'));
            
            // Reset hidden inputs
            document.getElementById('incidentTypeInput').value = '';
            document.getElementById('latitudeInput').value = '';
            document.getElementById('longitudeInput').value = '';
            document.getElementById('urgencyLevelInput').value = '';
            
            // Reset visible inputs
            document.getElementById('locationInput').value = '';
            document.getElementById('incidentDateInput').value = '';
            document.getElementById('incidentTimeInput').value = '';
            document.getElementById('descriptionInput').value = '';
            document.getElementById('userNameInput').value = '';
            document.getElementById('userPhoneInput').value = '';
            
            // Reset urgency buttons
            urgencyButtons.forEach(btn => {
                btn.classList.remove('border-green-500', 'border-yellow-500', 'border-red-500');
                btn.classList.add('border-gray-300');
            });
            selectedUrgencyButton = null;
            
            // Reset photo upload
            previewContainer.innerHTML = '';
            updatePhotoCount();
            photoUpload.value = '';
            
            // Reset form element
            document.getElementById('incidentReportForm').reset();
            
            // Set default date and time
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('incidentDateInput').value = today;
            
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('incidentTimeInput').value = `${hours}:${minutes}`;
        }
        
        // Helper function to show notifications
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotification = document.querySelector('.custom-notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            const notification = document.createElement('div');
            notification.className = `custom-notification fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300 ${getNotificationColor(type)}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${getNotificationIcon(type)} mr-3 text-lg"></i>
                    <div class="flex-1">${message}</div>
                    <button type="button" class="ml-4 text-xl hover:opacity-75 transition-opacity">&times;</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);
            
            // Auto remove after 5 seconds
            const autoRemove = setTimeout(() => {
                hideNotification(notification);
            }, 5000);
            
            // Close button
            notification.querySelector('button').addEventListener('click', () => {
                clearTimeout(autoRemove);
                hideNotification(notification);
            });
        }
        
        function hideNotification(notification) {
            notification.classList.remove('translate-x-0');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
        
        function getNotificationColor(type) {
            switch(type) {
                case 'success': return 'bg-green-50 text-green-800 border border-green-200';
                case 'error': return 'bg-red-50 text-red-800 border border-red-200';
                case 'warning': return 'bg-yellow-50 text-yellow-800 border border-yellow-200';
                default: return 'bg-blue-50 text-blue-800 border border-blue-200';
            }
        }
        
        function getNotificationIcon(type) {
            switch(type) {
                case 'success': return 'fa-check-circle';
                case 'error': return 'fa-exclamation-circle';
                case 'warning': return 'fa-exclamation-triangle';
                default: return 'fa-info-circle';
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            goToStep(1);
            
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('incidentDateInput').value = today;
            
            // Set default time to now
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('incidentTimeInput').value = `${hours}:${minutes}`;
        });
    </script>
@endsection
