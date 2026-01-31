<aside class="sidebar bg-[#1E88E5] w-64 fixed inset-y-0 left-0 border-r border-blue-300 shadow-xl z-40" id="sidebar">
    <!-- Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-blue-300 bg-[#1565C0]">
        <div class="flex items-center space-x-2">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Disaster Alert" class="h-8 w-8 rounded-full bg-white p-1">
            <h1 class="text-white font-bold text-lg">Pasong Tamo Alert</h1>
        </div>
        <!-- Mobile Close -->
        <button class="text-white hover:text-gray-200 md:hidden" onclick="toggleSidebar()">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <!-- Navigation -->
    <div class="h-full overflow-y-auto px-4 py-6 space-y-2">
        <ul class="space-y-1 text-white font-medium">

            <!-- Dashboard -->
            <li>
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <!-- Real-Time Alerts -->
            <li>
                <a href="<?php echo e(route('incidents.index')); ?>" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fa-solid fa-triangle-exclamation w-5"></i>
                    <span class="ml-3">Real-Time Alerts</span>
                </a>
            </li>

            <!-- Safe Zones -->
            <li>
                <a href="<?php echo e(route('announcements.index')); ?>" class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fa-solid fa-person-shelter w-5"></i>
                    <span class="ml-3">Report Announcement</span>
                </a>
            </li>

            
        </ul>
    </div>

    <!-- User Bottom -->
    <div class="absolute bottom-0 left-0 w-full p-5 bg-[#1565C0] border-t border-blue-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-white text-[#1565C0] flex items-center justify-center font-bold uppercase">
                    <?php echo e(strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1))); ?>

                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-white"><?php echo e(auth()->user()->first_name ?? 'User'); ?></p>
                    <p class="text-xs text-blue-100"><?php echo e(strtoupper(auth()->user()->role ?? 'Guest')); ?></p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-white hover:text-red-300 transition">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
<?php /**PATH C:\wamp64\www\Pasong-Tamo\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>