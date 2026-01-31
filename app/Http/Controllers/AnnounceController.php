<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AnnounceController extends Controller
{
    // Show announcement management page
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        
        $announcements = Announcement::with('user')
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($status && $status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::active()->count(),
            'scheduled' => Announcement::scheduled()->count(),
            'expired' => Announcement::expired()->count(),
        ];

        // Use the correct view path
        return view('announce.announcement', compact('announcements', 'stats', 'search', 'status'));
    }

    // Show announcement creation form (modal doesn't need separate view)
    public function create()
    {
        return response()->json([
            'success' => true,
            'html' => view('announce.modal')->render() // If you want a separate modal view
        ]);
    }

    // Store new announcement
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|required_with:end_date',
            'status' => 'required|in:draft,scheduled,active,expired',
            'priority' => 'required|in:low,medium,high'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startDateTime = $request->start_date . ' ' . $request->start_time;
            $endDateTime = null;
            
            if ($request->end_date && $request->end_time) {
                $endDateTime = $request->end_date . ' ' . $request->end_time;
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'announcement_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $filename, 'public');
            }

            $announcement = Announcement::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imagePath,
                'status' => $request->status,
                'priority' => $request->priority,
                'start_at' => $startDateTime,
                'end_at' => $endDateTime,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement ' . ($request->status === 'draft' ? 'saved as draft' : 'published') . ' successfully!',
                'announcement' => $announcement
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get announcement data for editing
    public function edit(Announcement $announcement)
    {
        return response()->json([
            'success' => true,
            'announcement' => [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'description' => $announcement->description,
                'image_url' => $announcement->image ? asset('storage/' . $announcement->image) : null,
                'status' => $announcement->status,
                'priority' => $announcement->priority,
                'start_date' => $announcement->start_at->format('Y-m-d'),
                'start_time' => $announcement->start_at->format('H:i'),
                'end_date' => $announcement->end_at ? $announcement->end_at->format('Y-m-d') : null,
                'end_time' => $announcement->end_at ? $announcement->end_at->format('H:i') : null,
            ]
        ]);
    }

    // Update announcement
    public function update(Request $request, Announcement $announcement)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'end_time' => 'nullable|required_with:end_date',
            'status' => 'required|in:draft,scheduled,active,expired',
            'priority' => 'required|in:low,medium,high'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $startDateTime = $request->start_date . ' ' . $request->start_time;
            $endDateTime = null;
            
            if ($request->end_date && $request->end_time) {
                $endDateTime = $request->end_date . ' ' . $request->end_time;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                
                $image = $request->file('image');
                $filename = 'announcement_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('announcements', $filename, 'public');
                $announcement->image = $imagePath;
            }

            $announcement->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'priority' => $request->priority,
                'start_at' => $startDateTime,
                'end_at' => $endDateTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully!',
                'announcement' => $announcement
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete announcement
    public function destroy(Announcement $announcement)
    {
        try {
            // Delete image if exists
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            
            $announcement->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Announcement deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:announcements,id'
        ]);

        try {
            $announcements = Announcement::whereIn('id', $request->ids);

            switch ($request->action) {
                case 'activate':
                    $announcements->update(['status' => 'active']);
                    $message = 'Announcements activated successfully!';
                    break;
                    
                case 'deactivate':
                    $announcements->update(['status' => 'draft']);
                    $message = 'Announcements deactivated successfully!';
                    break;
                    
                case 'delete':
                    // Delete images first
                    $announcements->each(function($announcement) {
                        if ($announcement->image) {
                            Storage::disk('public')->delete($announcement->image);
                        }
                    });
                    $announcements->delete();
                    $message = 'Announcements deleted successfully!';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get announcement statistics
    public function getStats()
    {
        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::active()->count(),
            'scheduled' => Announcement::scheduled()->count(),
            'expired' => Announcement::expired()->count(),
        ];

        return response()->json($stats);
    }
}