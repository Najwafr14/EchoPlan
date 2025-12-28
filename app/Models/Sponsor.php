<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $table = 'sponsor';
    protected $primaryKey = 'sponsor_id';

    protected $fillable = [
        'event_id',
        'sponsor_name',
        'contribution_amount',
        'sponsor_type',
        'contact_info',
    ];

    protected $casts = [
        'contribution_amount' => 'decimal:2',
    ];

    // Relasi Many-to-One ke Events
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}