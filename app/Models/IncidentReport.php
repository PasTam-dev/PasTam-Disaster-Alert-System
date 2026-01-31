<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class IncidentReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incident_reports';

    protected $fillable = [
        'report_id',
        'incident_type',
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'incident_date',
        'incident_time',
        'urgency_level',
        'status',
        'reporter_name',
        'reporter_phone',
        'notes',
        'assigned_to',
        'resolved_at',
        'due_date',
        'response_time_minutes',
        'category_id'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'assigned_to' => 'array',
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
        'response_time_minutes' => 'integer',
    ];

    protected $appends = [
        'formatted_date',
        'formatted_time',
        'elapsed_time',
        'is_overdue',
        'priority_color'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_OVERDUE = 'overdue';

    const URGENCY_LOW = 'low';
    const URGENCY_MEDIUM = 'medium';
    const URGENCY_HIGH = 'high';

    const TYPE_NATURAL = 'natural_disaster';
    const TYPE_ACCIDENT = 'accident';
    const TYPE_CRIME = 'crime_security';
    const TYPE_INFRASTRUCTURE = 'infrastructure';
    const TYPE_HEALTH = 'health_emergency';
    const TYPE_OTHER = 'other';

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->report_id)) {
                $model->report_id = self::generateReportId();
            }
            
            // Set due date based on urgency
            $model->due_date = self::calculateDueDate($model->urgency_level);
        });

        static::updating(function ($model) {
            // Track status changes
            if ($model->isDirty('status')) {
                $model->recordStatusChange();
                
                // If status is resolved, set resolved_at
                if ($model->status === self::STATUS_RESOLVED && !$model->resolved_at) {
                    $model->resolved_at = now();
                    
                    // Calculate response time
                    $model->calculateResponseTime();
                }
                
                // Check if overdue
                if ($model->status !== self::STATUS_RESOLVED && 
                    $model->status !== self::STATUS_CANCELLED &&
                    $model->due_date && 
                    $model->due_date->isPast()) {
                    $model->status = self::STATUS_OVERDUE;
                }
            }
        });
    }

    /**
     * Generate unique report ID
     */
    public static function generateReportId(): string
    {
        $prefix = 'REP-' . date('Ymd') . '-';
        $lastReport = self::where('report_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReport) {
            $lastNumber = intval(substr($lastReport->report_id, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Calculate due date based on urgency level
     */
    public static function calculateDueDate(string $urgencyLevel): \DateTime
    {
        $now = now();
        
        switch ($urgencyLevel) {
            case self::URGENCY_HIGH:
                return $now->addHours(2); // 2 hours for high urgency
            case self::URGENCY_MEDIUM:
                return $now->addHours(24); // 24 hours for medium
            case self::URGENCY_LOW:
                return $now->addDays(3); // 3 days for low
            default:
                return $now->addDays(1);
        }
    }

    /**
     * Record status change in history
     */
  public function recordStatusChange($notes = null): void
    {
        // Only record if status actually changed
        if ($this->isDirty('status')) {
            $this->statusHistory()->create([
                'old_status' => $this->getOriginal('status'),
                'new_status' => $this->status,
                'changed_by' => auth()->id() ?? null,
                'notes' => $notes ?? 'Status updated'
            ]);
        }
    }

    /**
     * Calculate response time in minutes
     */
    public function calculateResponseTime(): void
    {
        if ($this->resolved_at && $this->created_at) {
            $this->response_time_minutes = $this->created_at->diffInMinutes($this->resolved_at);
        }
    }

    /**
     * Get incident type label
     */
    public function getIncidentTypeLabelAttribute(): string
    {
        $types = [
            self::TYPE_NATURAL => 'Natural Disaster',
            self::TYPE_ACCIDENT => 'Accident',
            self::TYPE_CRIME => 'Crime / Security',
            self::TYPE_INFRASTRUCTURE => 'Infrastructure Problem',
            self::TYPE_HEALTH => 'Health Emergency',
            self::TYPE_OTHER => 'Other Problem'
        ];

        return $types[$this->incident_type] ?? 'Unknown';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_OVERDUE => 'Overdue'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Get urgency label
     */
    public function getUrgencyLabelAttribute(): string
    {
        $urgencies = [
            self::URGENCY_LOW => 'Not Urgent',
            self::URGENCY_MEDIUM => 'Somewhat Urgent',
            self::URGENCY_HIGH => 'Very Urgent'
        ];

        return $urgencies[$this->urgency_level] ?? 'Unknown';
    }

    /**
     * Check if incident is overdue
     */
    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status !== self::STATUS_RESOLVED && 
                         $this->status !== self::STATUS_CANCELLED &&
                         $this->due_date && 
                         $this->due_date->isPast()
        );
    }

    /**
     * Get formatted date
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->incident_date?->format('F j, Y')
        );
    }

    /**
     * Get formatted time
     */
    protected function formattedTime(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->incident_time ? date('g:i A', strtotime($this->incident_time)) : ''
        );
    }

    /**
     * Get elapsed time since creation
     */
    protected function elapsedTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->created_at) return '';
                
                $interval = $this->created_at->diff(now());
                
                if ($interval->d > 0) {
                    return $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
                } elseif ($interval->h > 0) {
                    return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
                } else {
                    return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
                }
            }
        );
    }

    /**
     * Get priority color for UI
     */
    protected function priorityColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === self::STATUS_OVERDUE) {
                    return '#EF4444'; // Red for overdue
                }
                
                switch ($this->urgency_level) {
                    case self::URGENCY_HIGH:
                        return '#EF4444'; // Red
                    case self::URGENCY_MEDIUM:
                        return '#F59E0B'; // Amber
                    case self::URGENCY_LOW:
                        return '#10B981'; // Green
                    default:
                        return '#6B7280'; // Gray
                }
            }
        );
    }
    public function getTypeColor(): string
{
    $colors = [
        self::TYPE_NATURAL => 'bg-red-100 text-red-800',
        self::TYPE_ACCIDENT => 'bg-yellow-100 text-yellow-800',
        self::TYPE_CRIME => 'bg-purple-100 text-purple-800',
        self::TYPE_INFRASTRUCTURE => 'bg-blue-100 text-blue-800',
        self::TYPE_HEALTH => 'bg-green-100 text-green-800',
        self::TYPE_OTHER => 'bg-gray-100 text-gray-800'
    ];

    return $colors[$this->incident_type] ?? 'bg-gray-100 text-gray-800';
}

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        $colors = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_UNDER_REVIEW => 'bg-blue-100 text-blue-800',
            self::STATUS_IN_PROGRESS => 'bg-purple-100 text-purple-800',
            self::STATUS_RESOLVED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            self::STATUS_OVERDUE => 'bg-red-100 text-red-800'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Scope for pending incidents
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for overdue incidents
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->orWhere(function ($q) {
                        $q->where('status', '!=', self::STATUS_RESOLVED)
                          ->where('status', '!=', self::STATUS_CANCELLED)
                          ->where('due_date', '<', now());
                    });
    }

    /**
     * Scope for resolved incidents
     */
    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    /**
     * Scope by urgency level
     */
    public function scopeUrgency($query, $level)
    {
        return $query->where('urgency_level', $level);
    }

    /**
     * Scope for high priority incidents
     */
    public function scopeHighPriority($query)
    {
        return $query->where('urgency_level', self::URGENCY_HIGH)
                    ->whereIn('status', [self::STATUS_PENDING, self::STATUS_UNDER_REVIEW]);
    }

    /**
     * Relationship with photos
     */
    public function photos()
    {
        return $this->hasMany(IncidentPhoto::class);
    }

    /**
     * Relationship with status history
     */
    public function statusHistory()
    {
        return $this->hasMany(IncidentStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Relationship with category
     */
  

    /**
     * Get latest status change
     */
    public function latestStatus()
    {
        return $this->hasOne(IncidentStatusHistory::class)->latest();
    }
}