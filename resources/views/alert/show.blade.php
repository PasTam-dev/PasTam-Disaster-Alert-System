@extends('layouts.app')

@section('title', 'Incident Details')

@section('content')
<div class="container max-w-full px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('incidents.index') }}" class="mr-4 text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Incident Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $incident->title }}</h2>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm {{ $incident->getStatusColor() }}">
                                    {{ $incident->status_label }}
                                </span>
                                <span class="text-gray-600">
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $incident->created_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">{{ $incident->report_id }}</div>
                            <div class="text-sm text-gray-500">Report ID</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Incident Type</h3>
                            <div class="flex items-center">
                                @switch($incident->incident_type)
                                    @case('natural_disaster')
                                        <i class="fas fa-cloud-showers-heavy text-red-500 mr-2"></i>
                                        @break
                                    @case('accident')
                                        <i class="fas fa-car-crash text-yellow-500 mr-2"></i>
                                        @break
                                    @case('crime_security')
                                        <i class="fas fa-shield-alt text-purple-500 mr-2"></i>
                                        @break
                                    @case('infrastructure')
                                        <i class="fas fa-road text-blue-500 mr-2"></i>
                                        @break
                                    @case('health_emergency')
                                        <i class="fas fa-heartbeat text-green-500 mr-2"></i>
                                        @break
                                    @default
                                        <i class="fas fa-question-circle text-gray-500 mr-2"></i>
                                @endswitch
                                <span class="text-gray-800">{{ $incident->incident_type_label }}</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Urgency Level</h3>
                            @if($incident->urgency_level == 'high')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-ambulance mr-1"></i> Very Urgent
                                </span>
                            @elseif($incident->urgency_level == 'medium')
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-running mr-1"></i> Somewhat Urgent
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-walking mr-1"></i> Not Urgent
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Incident Date & Time</h3>
                            <p class="text-gray-800">
                                <i class="far fa-calendar mr-1"></i>
                                {{ $incident->formatted_date }}
                            </p>
                            <p class="text-gray-800 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                {{ $incident->formatted_time }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Reporter Information</h3>
                            @if($incident->reporter_name)
                                <p class="text-gray-800">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $incident->reporter_name }}
                                </p>
                                <p class="text-gray-800 mt-1">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ $incident->reporter_phone }}
                                </p>
                            @else
                                <p class="text-gray-500 italic">Reported anonymously</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Location</h3>
                        <p class="text-gray-800">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            {{ $incident->location }}
                        </p>
                        @if($incident->latitude && $incident->longitude)
                        <div class="mt-2">
                            <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" 
                               target="_blank" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                View on Google Maps
                            </a>
                        </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-800 whitespace-pre-line">{{ $incident->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photos Section -->
            @if($incident->photos->count() > 0)
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Photos ({{ $incident->photos->count() }})</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($incident->photos as $photo)
                        <div class="relative group">
                            <img src="{{ Storage::url($photo->photo_path) }}" 
                                 alt="Incident photo" 
                                 class="w-full h-48 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <a href="{{ Storage::url($photo->photo_path) }}" 
                                   target="_blank" 
                                   class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Status History -->
            @if($incident->statusHistory->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Status History</h3>
                    <div class="space-y-4">
                        @foreach($incident->statusHistory as $history)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-800">
                                        Changed to <span class="capitalize">{{ $history->new_status }}</span>
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $history->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($history->notes && $history->notes !== 'Status updated')
                                <p class="text-gray-600 mt-1 text-sm">{{ $history->notes }}</p>
                                @endif
                                @if($history->changedBy)
                                <p class="text-gray-500 text-sm mt-1">By: {{ $history->changedBy->name }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Update Status -->
        <div>
            <div class="bg-white rounded-lg shadow sticky top-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Update Status</h3>
                    <form id="updateStatusForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <option value="pending" {{ $incident->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="under_review" {{ $incident->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="in_progress" {{ $incident->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $incident->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="cancelled" {{ $incident->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Add notes about this status change..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('updateStatusForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("incidents.update-status", $incident->id) }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: formData.get('status'),
                notes: formData.get('notes')
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Status updated successfully!');
            location.reload();
        } else {
            alert('Failed to update status: ' + result.message);
        }
    } catch (error) {
        alert('Network error. Please try again.');
    }
});
</script>
@endsection