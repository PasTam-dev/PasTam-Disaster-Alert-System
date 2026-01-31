<?php $__env->startSection('title', 'Pasong Tamo Barangay Emergency Alert System'); ?>
<?php $__env->startSection('content'); ?>
   <style>
        * {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
        }
        h1, h2, h3, h4 {
            font-family: 'Poppins', sans-serif;
        }

        /* Modal Styles */
        #announcementModal {
            backdrop-filter: blur(5px);
        }

        #announcementModal.show {
            display: flex !important;
        }

        #announcementModal {
            display: none;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.6);
            transition: opacity 0.3s ease;
        }

        .modal-content {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Prevent background scrolling */
        body.modal-open {
            overflow: hidden;
        }

        /* Alert Styles */
        .alert-storm { 
            background-color: #EFF6FF;
            border-left: 8px solid #3B82F6;
        }
        .alert-earthquake { 
            background-color: #FEF2F2;
            border-left: 8px solid #EF4444;
        }
        .alert-announcement { 
            background-color: #F0FDF4;
            border-left: 8px solid #10B981;
        }
        .alert-other { 
            background-color: #FFFBEB;
            border-left: 8px solid #F59E0B;
        }

        .alert-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1F2937;
        }

        .alert-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #4B5563;
            margin-top: 1rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-badge-active {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        .status-badge-scheduled {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .status-badge-draft {
            background-color: #F3F4F6;
            color: #4B5563;
        }

        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }

        .priority-high {
            background-color: #FEE2E2;
            color: #DC2626;
        }

        .priority-medium {
            background-color: #FEF3C7;
            color: #D97706;
        }

        .priority-low {
            background-color: #D1FAE5;
            color: #059669;
        }

        .carousel-container {
            position: relative;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.4s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 100%;
            padding: 0 1.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #9CA3AF;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: #1F2937;
        }

        .nav-btn {
            background-color: #F3F4F6;
            border: none;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
            color: #4B5563;
        }

        .nav-btn:hover {
            background-color: #E5E7EB;
        }

        .indicator-btn {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            background-color: #D1D5DB;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .indicator-btn.active {
            background-color: #3B82F6;
        }

        .z-50 {
            z-index: 99999 !important;
        }
    </style>
<header 
    class="bg-cover bg-center shadow-sm min-h-[60vh] flex items-center"
    style="background-image: url('<?php echo e(asset('images/pasong.jpg')); ?>');"
>
</header>
 <!-- Announcement Modal -->
    <?php if($announcements->isNotEmpty()): ?>
    <div id="announcementModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Backdrop -->
        <div class="modal-backdrop fixed inset-0" id="modalBackdrop"></div>
        
        <!-- Modal Content -->
        <div class="modal-content relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white flex justify-between items-center p-6 border-b border-gray-200 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-bullhorn text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Announcements</h3>
                        <p class="text-sm text-gray-500">Important updates for Pasong Tamo</p>
                    </div>
                </div>
                <button id="closeModalBtn" class="close-btn" aria-label="Close modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Carousel Container -->
            <div class="carousel-container p-6">
                <div class="carousel-track" id="carouselTrack">
                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-item" data-index="<?php echo e($index); ?>">
                        <!-- Announcement Image -->
                        <?php if($announcement->image): ?>
                        <div class="mb-6 overflow-hidden rounded-xl">
                            <img src="<?php echo e(asset('storage/' . $announcement->image)); ?>" 
                                 alt="<?php echo e($announcement->title); ?>" 
                                 class="w-full h-64 object-cover">
                        </div>
                        <?php endif; ?>

                        <!-- Announcement Content -->
                        <div class="space-y-4">
                            <!-- Title & Badges -->
                            <div>
                                <h4 class="alert-title"><?php echo e($announcement->title); ?></h4>
                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                    <span class="status-badge status-badge-<?php echo e($announcement->status); ?>">
                                        <?php echo e(ucfirst($announcement->status)); ?>

                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        <?php echo e($announcement->getFormattedStartDate()); ?>

                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="alert-description"><?php echo e($announcement->description); ?></p>

                            <!-- Priority Badge -->
                            <?php if($announcement->priority !== 'medium'): ?>
                            <div class="priority-badge priority-<?php echo e($announcement->priority); ?>">
                                <i class="fas fa-<?php echo e($announcement->priority == 'high' ? 'exclamation-circle' : 'info-circle'); ?>"></i>
                                <span><?php echo e(ucfirst($announcement->priority)); ?> Priority</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Navigation Controls -->
                <?php if($announcements->count() > 1): ?>
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <button id="prevBtn" class="nav-btn" aria-label="Previous announcement">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="flex gap-2" id="indicatorsContainer">
                        <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="indicator-btn <?php echo e($index === 0 ? 'active' : ''); ?>" 
                                data-index="<?php echo e($index); ?>" 
                                aria-label="Go to announcement <?php echo e($index + 1); ?>">
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <button id="nextBtn" class="nav-btn" aria-label="Next announcement">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <span id="currentIndex">1</span> of <?php echo e($announcements->count()); ?>

                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="dontShowAgain" class="w-4 h-4 rounded">
                        <span class="text-sm text-gray-700">Don't show today</span>
                    </label>
                    <button id="closeAllBtn" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-900 rounded-lg font-medium transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    

            <!-- Modal Footer -->
            <div class="flex justify-between items-center p-6 border-t bg-gray-50 rounded-b-2xl">
                <div class="text-sm text-gray-500">
                    Showing <?php echo e($announcements->count()); ?> announcement(s)
                </div>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" id="dontShowAgain" class="rounded text-primary">
                        <span class="text-sm text-gray-600">Don't show again today</span>
                    </label>
                    <button id="closeAllBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
    <!-- Main Content -->
   <main class="w-full">
        <!-- PAGASA Section - FULL WIDTH -->
        <section id="pagasa-section" class="full-width-section bg-white shadow-lg overflow-hidden">
            <div class="section-header px-6">
                <div class="container mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-3 md:mb-0">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                                <i class="fas fa-cloud-sun-rain text-blue-600 mr-2"></i>
                                PAGASA - Weather & Typhoon Alerts
                            </h2>
                            <p class="text-gray-600 text-sm">Official weather forecasts and tropical cyclone updates</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="refreshPAGASA()" class="refresh-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg flex items-center text-sm">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh
                            </button>
                            <a href="https://www.pagasa.dost.gov.ph" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-lg flex items-center text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i> Full Site
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Full-screen IFrame Container -->
          <div style="width:100vw; min-height:120vh; position:relative; overflow:hidden;">
    
                <div style="position:absolute; inset:0; z-index:2; pointer-events:none;"></div>

                <div id="pagasa-forecast" style="width:100%; height:100%;">
                    <iframe 
                        src="https://www.pagasa.dost.gov.ph/weather"
                        title="PAGASA Weather Forecast"
                        loading="lazy"
                        style="width:100%; height:100vh; border:none;">
                    </iframe>
                </div>

            </div>

            
            <!-- Info Footer -->
            <div class="bg-gray-50 p-3 text-xs text-gray-600 border-t">
                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                Official PAGASA (Philippine Atmospheric, Geophysical and Astronomical Services Administration) data. Updates are live from source.
            </div>
        </section>

        <!-- PHIVOLCS Section - FULL WIDTH -->
        <section id="phivolcs-section" class="full-width-section bg-white shadow-lg overflow-hidden">
            <div class="section-header px-6">
                <div class="container mx-auto">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-3 md:mb-0">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                                <i class="fas fa-mountain text-orange-600 mr-2"></i>
                                PHIVOLCS - Earthquake & Volcano Monitoring
                            </h2>
                            <p class="text-gray-600 text-sm">Latest earthquake information and volcano bulletins</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="refreshPHIVOLCS()" class="refresh-btn bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg flex items-center text-sm">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh
                            </button>
                            <a href="https://www.phivolcs.dost.gov.ph" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-lg flex items-center text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i> Full Site
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Full-screen IFrame Container -->
         <div style="width:100vw; min-height:120vh; position:relative; overflow:hidden;">

            <div style="position:absolute; inset:0; z-index:2; pointer-events:none;"></div>

            <div id="phivolcs-earthquake" style="width:100%; height:100%;">
                <iframe 
                    src="https://earthquake.phivolcs.dost.gov.ph"
                    title="PHIVOLCS Earthquake Information"
                    loading="lazy"
                    style="width:100%; height:100vh; border:none;">
                </iframe>
            </div>

        </div>

            
            <!-- Info Footer -->
            <div class="bg-gray-50 p-3 text-xs text-gray-600 border-t">
                <i class="fas fa-info-circle text-orange-500 mr-1"></i>
                Official PHIVOLCS (Philippine Institute of Volcanology and Seismology) data. Shows latest earthquake and volcano alerts.
            </div>
        </section>
    </main>
   
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'Asia/Manila'
        };
        document.getElementById('current-time').textContent = now.toLocaleDateString('en-PH', options);
    }
    
    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
    
    // Refresh functions for each iframe
    function refreshPAGASA() {
        const iframe = document.querySelector('#pagasa-forecast iframe');
        if (iframe) {
            iframe.src = iframe.src;
            showRefreshNotification('PAGASA');
        }
    }
    
    function refreshPHIVOLCS() {
        const iframe = document.querySelector('#phivolcs-earthquake iframe');
        if (iframe) {
            iframe.src = iframe.src;
            showRefreshNotification('PHIVOLCS');
        }
    }
    
    function refreshNDRRMC() {
        const iframe = document.getElementById('ndrrmc-iframe');
        if (iframe) {
            iframe.src = iframe.src;
            showRefreshNotification('NDRRMC');
        }
    }
    
    function refreshMMDA() {
        const iframe = document.getElementById('mmda-iframe');
        if (iframe) {
            iframe.src = iframe.src;
            showRefreshNotification('MMDA');
        }
    }
    
    // Show refresh notification
    function showRefreshNotification(source) {
        // Remove existing notification if any
        const existingNotification = document.querySelector('.refresh-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = 'refresh-notification fixed top-20 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate__animated animate__slideInRight';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <div>${source} data refreshed</div>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('animate__slideOutRight');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 500);
        }, 3000);
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Get header height for offset
                const headerHeight = document.querySelector('header').offsetHeight;
                window.scrollTo({
                    top: targetElement.offsetTop - headerHeight - 20,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Auto-refresh iframes every 15 minutes
    setInterval(() => {
        const iframes = document.querySelectorAll('iframe');
        iframes.forEach(iframe => {
            iframe.src = iframe.src;
        });
        console.log('All iframes auto-refreshed at ' + new Date().toLocaleTimeString());
    }, 900000); // 15 minutes
    
    // Add animation library for notifications
    const animateCSS = document.createElement('link');
    animateCSS.rel = 'stylesheet';
    animateCSS.href = 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css';
    document.head.appendChild(animateCSS);
</script>
 <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('announcementModal');
            
            if (!modal) {
                console.log('No announcements to display');
                return;
            }

            const track = document.getElementById('carouselTrack');
            const indicators = document.querySelectorAll('.indicator-btn');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const closeAllBtn = document.getElementById('closeAllBtn');
            const modalBackdrop = document.getElementById('modalBackdrop');
            const dontShowAgain = document.getElementById('dontShowAgain');
            const currentIndexSpan = document.getElementById('currentIndex');

            let currentIndex = 0;
            const totalAnnouncements = indicators.length;

            function showModal() {
                modal.classList.add('show');
                document.body.classList.add('modal-open');
            }

            function hideModal() {
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');

                if (dontShowAgain.checked) {
                    sessionStorage.setItem('announcementsDismissed', 'true');
                }
            }

            function updateCarousel() {
                const offset = -currentIndex * 100;
                track.style.transform = `translateX(${offset}%)`;
                
                indicators.forEach((indicator, idx) => {
                    indicator.classList.toggle('active', idx === currentIndex);
                });
                
                currentIndexSpan.textContent = currentIndex + 1;
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + totalAnnouncements) % totalAnnouncements;
                    updateCarousel();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % totalAnnouncements;
                    updateCarousel();
                });
            }

            indicators.forEach((indicator, idx) => {
                indicator.addEventListener('click', () => {
                    currentIndex = idx;
                    updateCarousel();
                });
            });

            [closeModalBtn, closeAllBtn, modalBackdrop].forEach(element => {
                if (element) {
                    element.addEventListener('click', hideModal);
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    hideModal();
                }
            });

            if (!sessionStorage.getItem('announcementsDismissed')) {
                showModal();
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plain', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Pasong-Tamo\resources\views/welcome.blade.php ENDPATH**/ ?>