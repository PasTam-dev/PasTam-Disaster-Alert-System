<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IncidentPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_report_id',
        'photo_path',
        'thumbnail_path',
        'caption',
        'sort_order'
    ];

    public function incident()
    {
        return $this->belongsTo(IncidentReport::class);
    }

    public function getPhotoUrlAttribute()
    {
        return Storage::url($this->photo_path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : $this->photo_url;
    }
}