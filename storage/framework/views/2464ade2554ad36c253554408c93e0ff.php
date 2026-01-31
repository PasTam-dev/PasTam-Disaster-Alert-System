<?php $__env->startSection('title', 'Incident Management Dashboard'); ?>

<?php $__env->startSection('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
<style>
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        transition: transform 0.2s ease-in-out;
        height: 100%;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .stat-card {
        text-align: center;
        padding: 1.5rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .incident-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .priority-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .announcement-card {
        border-left: 4px solid #3B82F6;
        background: linear-gradient(to right, rgba(59, 130, 246, 0.05), transparent);
    }
    
    .announcement-high-priority {
        border-left-color: #EF4444;
        background: linear-gradient(to right, rgba(239, 68, 68, 0.05), transparent);
    }
    
    .trend-up {
        color: #10B981;
    }
    
    .trend-down {
        color: #EF4444;
    }
    
    .status-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container max-w-full px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Incident Management Dashboard</h1>
        <p class="text-gray-600 mt-2">Real-time monitoring and analysis of incident reports</p>
        <div class="mt-4 text-sm text-gray-500">
            Last Updated: <?php echo e(now()->format('F j, Y h:i A')); ?>

        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.232 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">Overdue Incidents</div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e($incidentStats['overdue']); ?></div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">In Progress</div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e($incidentStats['in_progress']); ?></div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-500">This Month</div>
                    <div class="text-2xl font-bold text-gray-900"><?php echo e(end($incidentTrends['data'])); ?></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Incidents Card -->
        <div class="dashboard-card stat-card">
            <div class="flex items-center justify-center mb-4">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number text-blue-600"><?php echo e($incidentStats['total']); ?></div>
            <div class="stat-label">Total Incidents</div>
        </div>

        <!-- Pending Incidents Card -->
        <div class="dashboard-card stat-card">
            <div class="flex items-center justify-center mb-4">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number text-yellow-600"><?php echo e($incidentStats['pending']); ?></div>
            <div class="stat-label">Pending Review</div>
        </div>

        <!-- Resolved Incidents Card -->
        <div class="dashboard-card stat-card">
            <div class="flex items-center justify-center mb-4">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number text-green-600"><?php echo e($incidentStats['resolved']); ?></div>
            <div class="stat-label">Resolved</div>
        </div>

        <!-- Average Response Time Card -->
        <div class="dashboard-card stat-card">
            <div class="flex items-center justify-center mb-4">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number text-purple-600"><?php echo e($incidentStats['avg_response_time']); ?> min</div>
            <div class="stat-label">Avg Response Time</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Incident Trend Chart -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Incident Trends (Last 6 Months)</h3>
                <p class="text-sm text-gray-500">Monthly incident report volume</p>
            </div>
            <div class="chart-container">
                <canvas id="incidentTrendChart"></canvas>
            </div>
        </div>

        <!-- Incident Type Distribution -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Incident Type Distribution</h3>
                <p class="text-sm text-gray-500">Breakdown by incident category</p>
            </div>
            <div class="chart-container">
                <canvas id="incidentTypeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Urgency Level Distribution -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Urgency Level Distribution</h3>
                <p class="text-sm text-gray-500">Priority classification of incidents</p>
            </div>
            <div class="chart-container">
                <canvas id="urgencyChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Status Distribution</h3>
                <p class="text-sm text-gray-500">Current status of all incidents</p>
            </div>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Incidents and Announcements -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Incidents Table -->
        <div class="dashboard-card lg:col-span-2">
            <div class="card-header flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Recent Incidents</h3>
                    <p class="text-sm text-gray-500">Latest incident reports requiring attention</p>
                </div>
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All →</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Incident</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $recentIncidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-900"><?php echo e($incident['report_id']); ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900"><?php echo e($incident['title']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($incident['location']); ?></div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    <?php echo e($incident['incident_type']); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="priority-dot" style="background-color: <?php echo e($incident['priority_color']); ?>"></span>
                                    <span class="font-medium"><?php echo e(ucfirst($incident['urgency_level'])); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'under_review' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-purple-100 text-purple-800',
                                        'resolved' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'overdue' => 'bg-red-100 text-red-800'
                                    ];
                                ?>
                                <span class="incident-badge <?php echo e($statusColors[$incident['status']] ?? 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo e(str_replace('_', ' ', ucfirst($incident['status']))); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No incidents found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Active Announcements -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Active Announcements</h3>
                <p class="text-sm text-gray-500">Latest updates and notifications</p>
            </div>
            
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $activeAnnouncements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 rounded-lg <?php echo e($announcement->priority === 'high' ? 'announcement-high-priority' : 'announcement-card'); ?>">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-gray-900"><?php echo e($announcement->title); ?></h4>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($announcement->description, 100)); ?></p>
                        </div>
                        <span class="incident-badge <?php echo e($announcement->getStatusBadgeClass()); ?>">
                            <?php echo e(ucfirst($announcement->status)); ?>

                        </span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                        <span>Starts: <?php echo e($announcement->getFormattedStartDate()); ?></span>
                        <?php if($announcement->end_at): ?>
                        <span>Ends: <?php echo e($announcement->getFormattedEndDate()); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2">No active announcements</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Incident Trend Chart
    const trendCtx = document.getElementById('incidentTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($incidentTrends['months'], 15, 512) ?>,
            datasets: [{
                label: 'Incidents',
                data: <?php echo json_encode($incidentTrends['data'], 15, 512) ?>,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: Math.max(1, Math.ceil(Math.max(...<?php echo json_encode($incidentTrends['data'], 15, 512) ?>) / 10))
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Incident Type Distribution Chart
    const typeCtx = document.getElementById('incidentTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($incidentByType['labels'], 15, 512) ?>,
            datasets: [{
                data: <?php echo json_encode($incidentByType['data'], 15, 512) ?>,
                backgroundColor: <?php echo json_encode($incidentByType['colors'], 15, 512) ?>,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // Urgency Level Chart
    const urgencyCtx = document.getElementById('urgencyChart').getContext('2d');
    new Chart(urgencyCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($incidentByUrgency['labels'], 15, 512) ?>,
            datasets: [{
                label: 'Incidents',
                data: <?php echo json_encode($incidentByUrgency['data'], 15, 512) ?>,
                backgroundColor: <?php echo json_encode($incidentByUrgency['colors'], 15, 512) ?>,
                borderColor: <?php echo json_encode($incidentByUrgency['colors'], 15, 512) ?>,
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($incidentStatusDistribution['labels'], 15, 512) ?>,
            datasets: [{
                data: <?php echo json_encode($incidentStatusDistribution['data'], 15, 512) ?>,
                backgroundColor: <?php echo json_encode($incidentStatusDistribution['colors'], 15, 512) ?>,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Pasong-Tamo\resources\views/dashboard.blade.php ENDPATH**/ ?>