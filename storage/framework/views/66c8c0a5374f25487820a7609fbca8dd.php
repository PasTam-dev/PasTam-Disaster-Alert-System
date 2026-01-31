<?php $__env->startSection('title', 'Incident Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="container max-w-full px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Incident Reports</h1>
        <div class="flex space-x-4">
            <a href="<?php echo e(route('incidents.export')); ?>" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i> Export
            </a>
            <a href="<?php echo e(route('incidents.statistics')); ?>" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-chart-bar mr-2"></i> Statistics
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-blue-600"><?php echo e($total); ?></div>
            <div class="text-sm text-gray-600">Total Reports</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-yellow-600"><?php echo e($pending); ?></div>
            <div class="text-sm text-gray-600">Pending</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-red-600"><?php echo e($overdue); ?></div>
            <div class="text-sm text-gray-600">Overdue</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-green-600"><?php echo e($resolved); ?></div>
            <div class="text-sm text-gray-600">Resolved</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-purple-600"><?php echo e($highPriority); ?></div>
            <div class="text-sm text-gray-600">High Priority</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="<?php echo e(route('incidents.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="under_review" <?php echo e(request('status') == 'under_review' ? 'selected' : ''); ?>>Under Review</option>
                    <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                    <option value="resolved" <?php echo e(request('status') == 'resolved' ? 'selected' : ''); ?>>Resolved</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urgency</label>
                <select name="urgency" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Urgency</option>
                    <option value="high" <?php echo e(request('urgency') == 'high' ? 'selected' : ''); ?>>High</option>
                    <option value="medium" <?php echo e(request('urgency') == 'medium' ? 'selected' : ''); ?>>Medium</option>
                    <option value="low" <?php echo e(request('urgency') == 'low' ? 'selected' : ''); ?>>Low</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    <option value="natural_disaster" <?php echo e(request('type') == 'natural_disaster' ? 'selected' : ''); ?>>Natural Disaster</option>
                    <option value="accident" <?php echo e(request('type') == 'accident' ? 'selected' : ''); ?>>Accident</option>
                    <option value="crime_security" <?php echo e(request('type') == 'crime_security' ? 'selected' : ''); ?>>Security Incident</option>
                    <option value="infrastructure" <?php echo e(request('type') == 'infrastructure' ? 'selected' : ''); ?>>Infrastructure</option>
                    <option value="health_emergency" <?php echo e(request('type') == 'health_emergency' ? 'selected' : ''); ?>>Health Emergency</option>
                    <option value="other" <?php echo e(request('type') == 'other' ? 'selected' : ''); ?>>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Search..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="<?php echo e(route('incidents.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-mono text-sm text-blue-600"><?php echo e($incident->report_id); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e(Str::limit($incident->title, 50)); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e(Str::limit($incident->description, 70)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($incident->getTypeColor()); ?>">
                                <?php echo e($incident->incident_type_label); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e(Str::limit($incident->location, 30)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs <?php echo e($incident->getStatusColor()); ?>">
                                <?php echo e($incident->status_label); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($incident->urgency_level == 'high'): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> High
                                </span>
                            <?php elseif($incident->urgency_level == 'medium'): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Medium
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Low
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($incident->created_at->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('incidents.show', $incident->id)); ?>" 
                               class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p class="text-lg">No incident reports found.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4">
            <?php echo e($incidents->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Pasong-Tamo\resources\views/alert/report.blade.php ENDPATH**/ ?>