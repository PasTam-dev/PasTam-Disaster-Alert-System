<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class LandingController extends Controller
{
 public function index()
{
    // Get active announcements for homepage
    $announcements = Announcement::where(function($query) {
            $query->where('status', 'active')
                  ->where('start_at', '<=', now())
                  ->where(function($q) {
                      $q->whereNull('end_at')
                        ->orWhere('end_at', '>=', now());
                  });
        })
        ->orWhere(function($query) {
            $query->where('status', 'scheduled')
                  ->where('start_at', '<=', now()->addHours(24)); // Show scheduled announcements starting within 24 hours
        })
        ->orderBy('priority', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(5) // Limit to 5 announcements
        ->get();
        
    return view('welcome', compact('announcements'));
}

    public function map()   
    {
        return view('map');
    }
    public function emergency()
    {
        return view('emergency');
    }   
}
