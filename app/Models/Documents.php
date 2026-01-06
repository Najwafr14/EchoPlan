<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $primaryKey = 'document_id';
    protected $table = 'documents';

    protected $fillable = [
        'event_id',
        'document_name',
        'document_type',
        'uploaded_by',
        'path',
        'division_id',
        'entity_type',
        'entity_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'entity_id', 'budget_id');
    }

    public function entity()
    {
        if ($this->entity_type === 'Budget') {
            return $this->belongsTo(Budget::class, 'entity_id', 'budget_id');
        } elseif ($this->entity_type === 'Sponsor') {
            return $this->belongsTo(Sponsor::class, 'entity_id', 'sponsor_id');
        } elseif ($this->entity_type === 'Venue') {
            return $this->belongsTo(Venue::class, 'entity_id', 'venue_id');
        } elseif ($this->entity_type === 'Vendor') {
            return $this->belongsTo(Vendor::class, 'entity_id', 'vendor_id');
        } elseif ($this->entity_type === 'Talent') {
            return $this->belongsTo(Talent::class, 'entity_id', 'talent_id');
        } elseif ($this->entity_type === 'Item') {
            return $this->belongsTo(Barang::class, 'entity_id', 'item_id');
        }
        return null;
    }

    public function getFileExtension()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function getFileSize()
    {
        $fullPath = storage_path('app/public/' . $this->path);
        if (file_exists($fullPath)) {
            $bytes = filesize($fullPath);
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        }
        return 'Unknown';
    }
}