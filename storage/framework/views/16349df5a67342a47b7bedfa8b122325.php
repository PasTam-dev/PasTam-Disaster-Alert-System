<!DOCTYPE html>
<html lang=>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Pasong Tamo Risk Reduction System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.4/vue.global.prod.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />
    <style>
        .logo-placeholder {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #f59e0b 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Custom styles for sidebar toggle */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 16rem; /* w-64 */
            border-right: 1px solid #e5e7eb; gray-200
            box-shadow: 0 1px 2px rgba(0,0,0,.04);
            transform: translateX(0);
            transition: transform .3s ease-in-out;
            z-index: 40;
        }
        /* Hide sidebar (slides left) */
        .sidebar-closed {
            transform: translateX(-16rem);
        }

        /* Main content is shifted right when sidebar is open */
        .main-content {
            margin-left: 16rem;
            transition: margin-left .3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Expand content when sidebar is hidden */
        .main-content-expanded {
            margin-left: 0;
        }

        /* Optional: mobile fallback (no priority) */
        @media (max-width: 767px){
            .sidebar { width: 16rem; }
        }

        /* Custom styles for dropdowns */
        .dropdown-enter {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-open {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }

        /* Hover effects */
        .nav-item {
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background-color: #f3f4f6;
            border-radius: 0.5rem;
        }

        /* Active state */
        .nav-item.active {
            background-color: #e0e7ff;
            border-radius: 0.5rem;
        }

        .nav-item.active a {
            color: #4f46e5;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop fixed inset-0 bg-black bg-opacity-50 md:hidden" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="main-content min-h-screen flex flex-col" id="mainContent">
        <!-- Navbar -->
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <script>
       function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const isClosed = sidebar.classList.toggle('sidebar-closed');
            main.classList.toggle('main-content-expanded', isClosed);
        }

        // Dropdown functionality
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const chevron = document.getElementById(id.split('-')[0] + '-chevron');

            dropdown.classList.toggle('dropdown-open');
            chevron.classList.toggle('fa-chevron-down');
            chevron.classList.toggle('fa-chevron-up');
        }

        // Initialize sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-closed');
                mainContent.classList.remove('main-content-expanded');
            } else {
                sidebar.classList.add('sidebar-closed');
                mainContent.classList.add('main-content-expanded');
            }
        });

    // Force clean desktop defaults on load (sidebar open)
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        sidebar.classList.remove('sidebar-open', 'sidebar-backdrop-open', 'sidebar-closed');
        main.classList.remove('main-content-sidebar-open', 'main-content-expanded');
    });

        // Adjust sidebar on window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 768) {
                sidebar.classList.add('sidebar-open');
                backdrop.classList.remove('sidebar-backdrop-open');
                mainContent.classList.add('main-content-sidebar-open');
            } else {
                sidebar.classList.remove('sidebar-open');
                mainContent.classList.remove('main-content-sidebar-open');
            }
        });

        // Initialize sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 768) {
                sidebar.classList.add('sidebar-open');
                mainContent.classList.add('main-content-sidebar-open');
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\Pasong-Tamo\resources\views/layouts/app.blade.php ENDPATH**/ ?>