<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $table = 'venue';
    protected $primaryKey = 'venue_id';

    protected $fillable = [
        'event_id',
        'venue_name',
        'venue_address',
        'venue_capacity',
        'venue_price',
        'contact_person',
        'is_primary',
    ];

    protected $casts = [
        'venue_price' => 'decimal:2',
        'is_primary' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
