<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlertController extends Controller
{
    // Show form for public users
    public function create()
    {
        return view('report');
    }

    // Store new incident report (public form submission)
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'incident_type' => 'required|in:natural_disaster,accident,crime_security,infrastructure,health_emergency,other',
            'location' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
            'description' => 'required|string|min:10|max:2000',
            'urgency_level' => 'required|in:low,medium,high',
            'reporter_name' => 'nullable|string|max:100',
            'reporter_phone' => 'nullable|string|max:20',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create incident report
            $incident = IncidentReport::create([
                'incident_type' => $request->incident_type,
                'title' => $this->generateTitle($request->incident_type, $request->location),
                'description' => $request->description,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'incident_date' => $request->incident_date,
                'incident_time' => $request->incident_time,
                'urgency_level' => $request->urgency_level,
                'reporter_name' => $request->reporter_name,
                'reporter_phone' => $request->reporter_phone,
                'status' => IncidentReport::STATUS_PENDING,
                'notes' => 'Report submitted via web form',
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    // Store photo with proper path
                    $path = $photo->store('incident-photos/' . date('Y/m'), 'public');
                    
                    // Create photo record
                    $incident->photos()->create([
                        'photo_path' => $path,
                        'thumbnail_path' => $path,
                        'caption' => 'Incident photo',
                    ]);
                }
            }

            // Log status change
            $incident->recordStatusChange('Report submitted');

            return response()->json([
                'success' => true,
                'message' => 'Incident report submitted successfully',
                'data' => [
                    'report_id' => $incident->report_id,
                    'incident' => $incident
                ]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Incident report submission failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit incident report. Please try again.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    // Display all incidents (admin view)
    public function index(Request $request)
    {
        $query = IncidentReport::with(['photos'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by urgency
        if ($request->has('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('incident_type', $request->type);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Get statistics
        $total = IncidentReport::count();
        $pending = IncidentReport::pending()->count();
        $overdue = IncidentReport::overdue()->count();
        $resolved = IncidentReport::resolved()->count();
        $highPriority = IncidentReport::highPriority()->count();

        // Paginate results
        $incidents = $query->paginate(20)->withQueryString();

        return view('alert.report', compact('incidents', 'total', 'pending', 'overdue', 'resolved', 'highPriority'));
    }

    // Show single incident (admin view)
   public function show($id)
{
    try {
        $incident = IncidentReport::with(['photos', 'statusHistory'])->findOrFail($id);
        return view('alert.show', compact('incident'));
    } catch (\Exception $e) {
        \Log::error('Incident not found: ' . $e->getMessage());
        return redirect()->route('incidents.index')->with('error', 'Incident not found.');
    }
}

    // Update incident status (admin)
   public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,under_review,in_progress,resolved,cancelled',
        'notes' => 'nullable|string|max:1000',
    ]);

    try {
        $incident = IncidentReport::findOrFail($id);
        
        $incident->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $incident->notes,
        ]);

        // Record status change with custom notes
        $incident->recordStatusChange($validated['notes'] ?? 'Status updated');

        // If marked as resolved, calculate response time
        if ($validated['status'] === IncidentReport::STATUS_RESOLVED && !$incident->resolved_at) {
            $incident->update([
                'resolved_at' => now()
            ]);
            $incident->calculateResponseTime();
            $incident->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $incident
        ]);

    } catch (\Exception $e) {
        \Log::error('Failed to update incident status: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update status: ' . $e->getMessage()
        ], 500);
    }
}

    // Get incident statistics (API endpoint)
    public function getStatistics()
    {
        $total = IncidentReport::count();
        $pending = IncidentReport::pending()->count();
        $overdue = IncidentReport::overdue()->count();
        $resolved = IncidentReport::resolved()->count();
        $highPriority = IncidentReport::highPriority()->count();

        // Statistics by type
        $byType = IncidentReport::selectRaw('incident_type, count(*) as count')
            ->groupBy('incident_type')
            ->get();

        // Statistics by status
        $byStatus = IncidentReport::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Average response time for resolved incidents
        $avgResponseTime = IncidentReport::resolved()
            ->whereNotNull('response_time_minutes')
            ->avg('response_time_minutes');

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'pending' => $pending,
                'overdue' => $overdue,
                'resolved' => $resolved,
                'high_priority' => $highPriority,
                'by_type' => $byType,
                'by_status' => $byStatus,
                'avg_response_time' => round($avgResponseTime),
            ]
        ]);
    }

    // Export incidents (CSV/Excel)
    public function export(Request $request)
    {
        $incidents = IncidentReport::query();
        
        if ($request->has('start_date')) {
            $incidents->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $incidents->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->has('status')) {
            $incidents->where('status', $request->status);
        }
        
        $incidents = $incidents->get();
        
        // Generate CSV
        $filename = 'incidents_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($incidents) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Report ID',
                'Type',
                'Title',
                'Description',
                'Location',
                'Date',
                'Time',
                'Urgency',
                'Status',
                'Reporter Name',
                'Reporter Phone',
                'Created At',
                'Resolved At',
                'Response Time (minutes)'
            ]);
            
            // Data
            foreach ($incidents as $incident) {
                fputcsv($file, [
                    $incident->report_id,
                    $incident->incident_type_label,
                    $incident->title,
                    $incident->description,
                    $incident->location,
                    $incident->incident_date->format('Y-m-d'),
                    $incident->incident_time,
                    $incident->urgency_label,
                    $incident->status_label,
                    $incident->reporter_name,
                    $incident->reporter_phone,
                    $incident->created_at->format('Y-m-d H:i:s'),
                    $incident->resolved_at ? $incident->resolved_at->format('Y-m-d H:i:s') : '',
                    $incident->response_time_minutes
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Generate title based on type and location
     private function generateTitle($type, $location)
    {
        $types = [
            'natural_disaster' => 'Natural Disaster',
            'accident' => 'Accident',
            'crime_security' => 'Security Incident',
            'infrastructure' => 'Infrastructure Issue',
            'health_emergency' => 'Health Emergency',
            'other' => 'Incident Report'
        ];
        
        $typeLabel = $types[$type] ?? 'Incident';
        $shortLocation = Str::limit($location, 30);
        
        return $typeLabel . ' at ' . $shortLocation;
    }
}