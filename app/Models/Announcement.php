<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'priority',
        'start_at',
        'end_at',
        'user_id'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('end_at')
                          ->orWhere('end_at', '>=', now());
                    });
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('start_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function($q) {
                        $q->whereNotNull('end_at')
                          ->where('end_at', '<', now());
                    });
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%");
        }
        return $query;
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->start_at <= now() 
            && (is_null($this->end_at) || $this->end_at >= now());
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'scheduled' => 'bg-blue-100 text-blue-800',
            'draft' => 'bg-gray-100 text-gray-800',
            'expired' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getFormattedStartDate(): string
    {
        return $this->start_at->format('M d, Y h:i A');
    }

    public function getFormattedEndDate(): ?string
    {
        return $this->end_at?->format('M d, Y h:i A');
    }
}