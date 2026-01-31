<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\IncidentReport;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Get real incident statistics
        $incidentStats = $this->getIncidentStatistics();
        
        // Get real incident trends
        $incidentTrends = $this->getIncidentTrends();
        
        // Get real incident by type
        $incidentByType = $this->getIncidentByType();
        
        // Get real incident by urgency
        $incidentByUrgency = $this->getIncidentByUrgency();
        
        // Get real recent incidents
        $recentIncidents = $this->getRecentIncidents();
        
        // Get active announcements
        $activeAnnouncements = $this->getActiveAnnouncements();
        
        // Get incident status distribution
        $incidentStatusDistribution = $this->getIncidentStatusDistribution();
        
        return view('dashboard', compact(
            'incidentStats',
            'incidentTrends',
            'incidentByType',
            'incidentByUrgency',
            'recentIncidents',
            'activeAnnouncements',
            'incidentStatusDistribution'
        ));
    }
    
    private function getIncidentStatistics()
    {
        $total = IncidentReport::count();
        $pending = IncidentReport::where('status', 'pending')->count();
        $resolved = IncidentReport::where('status', 'resolved')->count();
        $overdue = IncidentReport::overdue()->count();
        $inProgress = IncidentReport::where('status', 'in_progress')->count();
        
        // Calculate average response time for resolved incidents
        $avgResponseTime = IncidentReport::where('status', 'resolved')
            ->whereNotNull('response_time_minutes')
            ->avg('response_time_minutes') ?? 0;
        
        // Calculate resolution rate
        $resolutionRate = $total > 0 ? round(($resolved / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'pending' => $pending,
            'resolved' => $resolved,
            'overdue' => $overdue,
            'in_progress' => $inProgress,
            'avg_response_time' => round($avgResponseTime),
            'resolution_rate' => $resolutionRate,
        ];
    }
    
    private function getIncidentTrends()
    {
        $months = [];
        $data = [];
        
        // Get incidents from last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths($i)->startOfMonth();
            $endDate = Carbon::now()->subMonths($i)->endOfMonth();
            $month = $startDate->format('M');
            
            $count = IncidentReport::whereBetween('created_at', [$startDate, $endDate])->count();
            
            $months[] = $month;
            $data[] = $count;
        }
        
        return [
            'months' => $months,
            'data' => $data,
        ];
    }
    
    private function getIncidentByType()
    {
        $types = IncidentReport::select('incident_type', DB::raw('count(*) as total'))
            ->groupBy('incident_type')
            ->get();
        
        $labels = [];
        $data = [];
        
        $typeLabels = [
            'natural_disaster' => 'Natural Disaster',
            'accident' => 'Accident',
            'crime_security' => 'Crime/Security',
            'infrastructure' => 'Infrastructure',
            'health_emergency' => 'Health Emergency',
            'other' => 'Other'
        ];
        
        $colors = [
            'natural_disaster' => '#EF4444',
            'accident' => '#F59E0B',
            'crime_security' => '#8B5CF6',
            'infrastructure' => '#3B82F6',
            'health_emergency' => '#10B981',
            'other' => '#6B7280'
        ];
        
        foreach ($types as $type) {
            $labels[] = $typeLabels[$type->incident_type] ?? $type->incident_type;
            $data[] = $type->total;
        }
        
        // If no data, return defaults
        if (empty($data)) {
            return [
                'labels' => ['Natural Disaster', 'Accident', 'Crime/Security', 'Infrastructure', 'Health', 'Other'],
                'data' => [0, 0, 0, 0, 0, 0],
                'colors' => ['#EF4444', '#F59E0B', '#8B5CF6', '#3B82F6', '#10B981', '#6B7280']
            ];
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_values($colors)
        ];
    }
    
    private function getIncidentByUrgency()
    {
        $urgencies = IncidentReport::select('urgency_level', DB::raw('count(*) as total'))
            ->groupBy('urgency_level')
            ->get();
        
        $labels = ['High', 'Medium', 'Low'];
        $data = [0, 0, 0];
        
        $urgencyMap = [
            'high' => 0,
            'medium' => 1,
            'low' => 2
        ];
        
        foreach ($urgencies as $urgency) {
            if (isset($urgencyMap[$urgency->urgency_level])) {
                $data[$urgencyMap[$urgency->urgency_level]] = $urgency->total;
            }
        }
        
        $colors = ['#EF4444', '#F59E0B', '#10B981'];
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        ];
    }
    
    private function getIncidentStatusDistribution()
    {
        $statuses = IncidentReport::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        $statusLabels = [];
        $statusData = [];
        $statusColors = [];
        
        $statusMap = [
            'pending' => ['Pending', '#F59E0B'],
            'under_review' => ['Under Review', '#3B82F6'],
            'in_progress' => ['In Progress', '#8B5CF6'],
            'resolved' => ['Resolved', '#10B981'],
            'cancelled' => ['Cancelled', '#6B7280'],
            'overdue' => ['Overdue', '#EF4444']
        ];
        
        foreach ($statuses as $status) {
            if (isset($statusMap[$status->status])) {
                $statusLabels[] = $statusMap[$status->status][0];
                $statusData[] = $status->total;
                $statusColors[] = $statusMap[$status->status][1];
            }
        }
        
        return [
            'labels' => $statusLabels,
            'data' => $statusData,
            'colors' => $statusColors
        ];
    }
    
    private function getRecentIncidents()
    {
        $incidents = IncidentReport::with(['photos'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($incident) {
                return [
                    'report_id' => $incident->report_id,
                    'title' => $incident->title,
                    'incident_type' => $incident->incident_type_label,
                    'status' => $incident->status,
                    'urgency_level' => $incident->urgency_level,
                    'location' => $incident->location,
                    'incident_date' => $incident->incident_date ? $incident->incident_date->format('M d, Y') : 'N/A',
                    'priority_color' => $this->getPriorityColor($incident->urgency_level, $incident->status)
                ];
            });
        
        return $incidents;
    }
    
    private function getActiveAnnouncements()
    {
        $announcements = Announcement::active()
            ->orderBy('priority', 'desc')
            ->orderBy('start_at', 'desc')
            ->limit(5)
            ->get();
        
        return $announcements;
    }
    
    private function getPriorityColor($urgency, $status)
    {
        if ($status === 'overdue') {
            return '#EF4444';
        }
        
        switch ($urgency) {
            case 'high':
                return '#EF4444';
            case 'medium':
                return '#F59E0B';
            case 'low':
                return '#10B981';
            default:
                return '#6B7280';
        }
    }
}