<?php $__env->startSection('title', 'Announcement Management'); ?>
<?php $__env->startSection('content'); ?>
    <!-- Main Content -->
    <main class="max-w-full flex-1 p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Announcement Management</h1>
                <p class="text-gray-600">Manage and schedule announcements for your landing page</p>
            </div>
            <button id="createAnnouncementBtn" class="bg-blue-500 hover:bg-secondary text-white font-medium py-3 px-6 rounded-lg transition-all flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Create New
            </button>
        </div>

        <!-- Filters and Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Active</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['active']); ?></p>
                    </div>
                    <div class="bg-green-500 p-3 rounded-full">
                        <i class="fas fa-play text-success text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Scheduled</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['scheduled']); ?></p>
                    </div>
                    <div class="bg-blue-300 p-3 rounded-full">
                        <i class="fas fa-clock text-primary text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Expired</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['expired']); ?></p>
                    </div>
                    <div class="bg-red-300 p-3 rounded-full">
                        <i class="fas fa-stop text-danger text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total</p>
                        <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['total']); ?></p>
                    </div>
                    <div class="bg-purple-200 p-3 rounded-full">
                        <i class="fas fa-bullhorn text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Controls -->
        <form method="GET" action="<?php echo e(route('announcements.index')); ?>" class="bg-white rounded-xl shadow border mb-6 p-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="search" placeholder="Search announcements..." value="<?php echo e($search); ?>" class="pl-10 pr-4 py-2 border rounded-lg w-64">
                    </div>
                    <select name="status" class="border rounded-lg px-4 py-2" onchange="this.form.submit()">
                        <option value="all" <?php echo e($status == 'all' ? 'selected' : ''); ?>>All Status</option>
                        <option value="active" <?php echo e($status == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="scheduled" <?php echo e($status == 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                        <option value="expired" <?php echo e($status == 'expired' ? 'selected' : ''); ?>>Expired</option>
                        <option value="draft" <?php echo e($status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                    </select>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="button" class="flex items-center text-gray-600 hover:text-primary">
                        <i class="fas fa-file-export mr-2"></i>
                        Export
                    </button>
                </div>
            </div>
        </form>

        <!-- Announcements Table -->
        <div class="bg-white rounded-xl shadow border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Announcement
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-id="<?php echo e($announcement->id); ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded announcement-checkbox" value="<?php echo e($announcement->id); ?>">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if($announcement->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $announcement->image)); ?>" alt="<?php echo e($announcement->title); ?>" class="w-12 h-12 rounded-lg object-cover mr-4">
                                    <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-bullhorn text-gray-400"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-gray-900"><?php echo e($announcement->title); ?></div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs"><?php echo e(Str::limit($announcement->description, 100)); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($announcement->getStatusBadgeClass()); ?>">
                                    <?php echo e(ucfirst($announcement->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium"><?php echo e($announcement->getFormattedStartDate()); ?></div>
                                <?php if($announcement->end_at): ?>
                                <div class="text-gray-500 text-xs">to <?php echo e($announcement->getFormattedEndDate()); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    <?php echo e($announcement->priority == 'high' ? 'bg-red-100 text-red-800' : ''); ?>

                                    <?php echo e($announcement->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                    <?php echo e($announcement->priority == 'low' ? 'bg-blue-100 text-blue-800' : ''); ?>">
                                    <?php echo e(ucfirst($announcement->priority)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($announcement->created_at->format('M d, Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <button class="text-primary hover:text-secondary edit-announcement" data-id="<?php echo e($announcement->id); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-danger hover:text-red-700 delete-announcement" data-id="<?php echo e($announcement->id); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-bullhorn text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No announcements found</p>
                                <p class="text-sm mt-2">Click "Create New" to add your first announcement</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <?php if($announcements->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-500 mb-4 md:mb-0">
                        Showing <span class="font-medium"><?php echo e($announcements->firstItem()); ?></span> to 
                        <span class="font-medium"><?php echo e($announcements->lastItem()); ?></span> of 
                        <span class="font-medium"><?php echo e($announcements->total()); ?></span> announcements
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php echo e($announcements->links()); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Create/Edit Announcement Modal -->
  <!-- Create/Edit Announcement Modal -->
<div id="announcementModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity" id="modalBackdrop"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary p-3 rounded-lg">
                        <i class="fas fa-bullhorn text-white text-xl"></i>
                    </div>
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Create New Announcement</h3>
                </div>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Image Preview Section -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Announcement Image</label>
                            <div id="imagePreview" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-primary transition-colors bg-gray-50 h-48 flex flex-col items-center justify-center">
                                <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Click to upload announcement image</p>
                                <p class="text-sm text-gray-400 mt-1">Recommended: 800x400px</p>
                            </div>
                            <input type="file" id="imageUpload" class="hidden" accept="image/*">
                        </div>

                        <!-- Title Input -->
                        <div>
                            <label for="announcementTitle" class="block text-gray-700 font-medium mb-2">Announcement Title *</label>
                            <input type="text" id="announcementTitle" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter announcement title">
                        </div>

                        <!-- Description Input -->
                        <div>
                            <label for="announcementDescription" class="block text-gray-700 font-medium mb-2">Announcement Description *</label>
                            <textarea id="announcementDescription" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter detailed description"></textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Date and Time Section -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Schedule</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="announcementDate" class="block text-sm text-gray-600 mb-1">Start Date *</label>
                                    <input type="date" id="announcementDate" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="announcementTime" class="block text-sm text-gray-600 mb-1">Start Time *</label>
                                    <input type="time" id="announcementTime" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="endDate" class="block text-sm text-gray-600 mb-1">End Date</label>
                                    <input type="date" id="endDate" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label for="endTime" class="block text-sm text-gray-600 mb-1">End Time</label>
                                    <input type="time" id="endTime" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <!-- Status and Priority -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="announcementStatus" class="block text-gray-700 font-medium mb-2">Status</label>
                                <select id="announcementStatus" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="active">Active</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="draft">Draft</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>
                            <div>
                                <label for="announcementPriority" class="block text-gray-700 font-medium mb-2">Priority</label>
                                <select id="announcementPriority" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex flex-col sm:flex-row justify-between p-6 border-t bg-gray-50 rounded-b-2xl">
                <div class="flex space-x-3">
                    <button id="cancelBtn" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Cancel
                    </button>
                     <button id="saveAnnouncementBtn" class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Publish Announcement
                    </button>
                    <button id="saveDraftBtn" class="px-6 py-3 bg-white hover:bg-white- text-white font-medium rounded-lg transition-colors hidden">
                        Save as Draft
                    </button>
                   
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // DOM Elements
    const modal = document.getElementById('announcementModal');
    const createAnnouncementBtn = document.getElementById('createAnnouncementBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const saveAnnouncementBtn = document.getElementById('saveAnnouncementBtn');
    const imagePreview = document.getElementById('imagePreview');
    const imageUpload = document.getElementById('imageUpload');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const modalTitle = document.getElementById('modalTitle');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Variables
    let isEditMode = false;
    let currentAnnouncementId = null;

    // Debug: Check if elements exist
    console.log('Modal:', modal);
    console.log('Create button:', createAnnouncementBtn);
    console.log('Close button:', closeModalBtn);

    // Open Modal for Creating
    createAnnouncementBtn.addEventListener('click', () => {
        console.log('Create button clicked');
        isEditMode = false;
        currentAnnouncementId = null;
        resetModal();
        modalTitle.textContent = 'Create New Announcement';
        saveAnnouncementBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Publish Announcement';
        saveDraftBtn.style.display = 'block';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Open Modal for Editing
    document.addEventListener('click', async (e) => {
        if (e.target.closest('.edit-announcement')) {
            e.preventDefault();
            const announcementId = e.target.closest('.edit-announcement').dataset.id;
            console.log('Edit clicked for ID:', announcementId);
            
            try {
                const response = await fetch(`/announcements/${announcementId}/edit`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    console.log('Edit data loaded:', data.announcement);
                    isEditMode = true;
                    currentAnnouncementId = announcementId;
                    populateModal(data.announcement);
                    modalTitle.textContent = 'Edit Announcement';
                    saveAnnouncementBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Update Announcement';
                    saveDraftBtn.style.display = 'none';
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    console.error('Failed to load data:', data);
                    alert('Failed to load announcement data');
                }
            } catch (error) {
                console.error('Error loading announcement:', error);
                alert('Failed to load announcement data');
            }
        }
    });

    // Populate modal with announcement data
    function populateModal(announcement) {
        console.log('Populating modal with:', announcement);
        
        document.getElementById('announcementTitle').value = announcement.title || '';
        document.getElementById('announcementDescription').value = announcement.description || '';
        document.getElementById('announcementDate').value = announcement.start_date || '';
        document.getElementById('announcementTime').value = announcement.start_time || '';
        document.getElementById('endDate').value = announcement.end_date || '';
        document.getElementById('endTime').value = announcement.end_time || '';
        document.getElementById('announcementStatus').value = announcement.status || 'draft';
        document.getElementById('announcementPriority').value = announcement.priority || 'medium';
        
        // Set image preview
        if (announcement.image_url) {
            imagePreview.innerHTML = `<img src="${announcement.image_url}" class="w-full h-full object-cover rounded-lg" alt="Announcement Image">`;
        } else {
            imagePreview.innerHTML = `
                <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Click to upload announcement image</p>
                <p class="text-sm text-gray-400 mt-1">Recommended: 800x400px</p>
            `;
        }
    }

    // Reset modal form
    function resetModal() {
        console.log('Resetting modal');
        
        // Clear all inputs
        document.getElementById('announcementTitle').value = '';
        document.getElementById('announcementDescription').value = '';
        document.getElementById('announcementDate').value = '';
        document.getElementById('announcementTime').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('endTime').value = '';
        document.getElementById('announcementStatus').value = 'active';
        document.getElementById('announcementPriority').value = 'medium';
        
        // Reset image preview
        imagePreview.innerHTML = `
            <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
            <p class="text-gray-500">Click to upload announcement image</p>
            <p class="text-sm text-gray-400 mt-1">Recommended: 800x400px</p>
        `;
        if (imageUpload) {
            imageUpload.value = '';
        }
        
        // Set default date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('announcementDate').value = tomorrow.toISOString().split('T')[0];
        
        // Set default time to current hour + 1
        const nextHour = new Date();
        nextHour.setHours(nextHour.getHours() + 1);
        document.getElementById('announcementTime').value = `${nextHour.getHours().toString().padStart(2, '0')}:00`;
    }

    // Close Modal
    const closeModal = () => {
        console.log('Closing modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

    // Image Upload Handling
    if (imagePreview) {
        imagePreview.addEventListener('click', () => {
            console.log('Image preview clicked');
            if (imageUpload) {
                imageUpload.click();
            }
        });
    }

    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            console.log('Image selected:', this.files[0]);
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-lg" alt="Announcement Image">`;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Save Announcement
    saveAnnouncementBtn.addEventListener('click', async function() {
        console.log('Save announcement clicked');
        
        // Validate required fields
        const title = document.getElementById('announcementTitle').value.trim();
        const description = document.getElementById('announcementDescription').value.trim();
        const startDate = document.getElementById('announcementDate').value;
        const startTime = document.getElementById('announcementTime').value;
        
        if (!title) {
            alert('Please enter an announcement title');
            document.getElementById('announcementTitle').focus();
            return;
        }
        
        if (!description) {
            alert('Please enter an announcement description');
            document.getElementById('announcementDescription').focus();
            return;
        }
        
        if (!startDate) {
            alert('Please select a start date');
            document.getElementById('announcementDate').focus();
            return;
        }
        
        if (!startTime) {
            alert('Please select a start time');
            document.getElementById('announcementTime').focus();
            return;
        }
        
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('start_date', startDate);
        formData.append('start_time', startTime);
        formData.append('end_date', document.getElementById('endDate').value);
        formData.append('end_time', document.getElementById('endTime').value);
        formData.append('status', document.getElementById('announcementStatus').value);
        formData.append('priority', document.getElementById('announcementPriority').value);
        
        if (imageUpload && imageUpload.files[0]) {
            formData.append('image', imageUpload.files[0]);
        }

        // Add CSRF token
        formData.append('_token', csrfToken);

        const url = isEditMode ? `/announcements/${currentAnnouncementId}` : '/announcements';
        const method = isEditMode ? 'PUT' : 'POST';

        console.log('Sending request to:', url, 'Method:', method);

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();
            console.log('Response:', data);

            if (data.success) {
                alert(data.message);
                closeModal();
                window.location.reload();
            } else {
                if (data.errors) {
                    let errorMessages = '';
                    for (const field in data.errors) {
                        errorMessages += data.errors[field].join('\n') + '\n';
                    }
                    alert('Validation errors:\n' + errorMessages);
                } else {
                    alert(data.message || 'An error occurred');
                }
            }
        } catch (error) {
            console.error('Error saving announcement:', error);
            alert('Failed to save announcement: ' + error.message);
        }
    });

    // Save Draft
    saveDraftBtn.addEventListener('click', async function() {
        console.log('Save draft clicked');
        
        const formData = new FormData();
        formData.append('title', document.getElementById('announcementTitle').value);
        formData.append('description', document.getElementById('announcementDescription').value);
        formData.append('start_date', document.getElementById('announcementDate').value);
        formData.append('start_time', document.getElementById('announcementTime').value);
        formData.append('end_date', document.getElementById('endDate').value);
        formData.append('end_time', document.getElementById('endTime').value);
        formData.append('status', 'draft');
        formData.append('priority', document.getElementById('announcementPriority').value);
        formData.append('_token', csrfToken);
        
        if (imageUpload && imageUpload.files[0]) {
            formData.append('image', imageUpload.files[0]);
        }

        try {
            const response = await fetch('/announcements', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();
            console.log('Draft save response:', data);

            if (data.success) {
                alert(data.message);
                closeModal();
                window.location.reload();
            } else {
                if (data.errors) {
                    let errorMessages = '';
                    for (const field in data.errors) {
                        errorMessages += data.errors[field].join('\n') + '\n';
                    }
                    alert('Validation errors:\n' + errorMessages);
                } else {
                    alert(data.message || 'An error occurred');
                }
            }
        } catch (error) {
            console.error('Error saving draft:', error);
            alert('Failed to save draft: ' + error.message);
        }
    });

    // Delete Announcement
    document.addEventListener('click', async (e) => {
        if (e.target.closest('.delete-announcement')) {
            if (confirm('Are you sure you want to delete this announcement?')) {
                const announcementId = e.target.closest('.delete-announcement').dataset.id;
                console.log('Deleting announcement ID:', announcementId);
                
                try {
                    const response = await fetch(`/announcements/${announcementId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });

                    const data = await response.json();
                    console.log('Delete response:', data);

                    if (data.success) {
                        alert(data.message);
                        // Remove the row from table
                        const row = document.querySelector(`tr[data-id="${announcementId}"]`);
                        if (row) row.remove();
                        window.location.reload(); // Reload to update stats
                    } else {
                        alert(data.message || 'Failed to delete announcement');
                    }
                } catch (error) {
                    console.error('Error deleting announcement:', error);
                    alert('Failed to delete announcement');
                }
            }
        }
    });

    // Bulk Actions
    document.getElementById('bulkActionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const selectedCheckboxes = document.querySelectorAll('.announcement-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one announcement');
            return;
        }

        const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
        const action = document.getElementById('bulkActionSelect').value;

        if (!action) {
            alert('Please select an action');
            return;
        }

        if (action === 'delete' && !confirm('Are you sure you want to delete the selected announcements?')) {
            return;
        }

        const formData = new FormData();
        formData.append('action', action);
        formData.append('ids', JSON.stringify(ids));
        formData.append('_token', csrfToken);

        try {
            const response = await fetch('/announcements/bulk-action', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Failed to perform bulk action');
            }
        } catch (error) {
            console.error('Error performing bulk action:', error);
            alert('Failed to perform bulk action');
        }
    });

    // Select All Checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.announcement-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    // Update selected count
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.announcement-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = selectedCount;
    }

    // Listen for checkbox changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('announcement-checkbox')) {
            updateSelectedCount();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Initialize modal with default values
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing modal');
        resetModal();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Pasong-Tamo\resources\views/announce/announcement.blade.php ENDPATH**/ ?>