<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_report_id',
        'old_status',
        'new_status',
        'notes',
        'changed_by'
    ];

    protected $casts = [
        'changed_by' => 'integer',
    ];

    public function incident()
    {
        return $this->belongsTo(IncidentReport::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}