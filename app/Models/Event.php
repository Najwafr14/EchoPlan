<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EventCategories;
use App\Models\User;
use App\Models\Division;
use App\Models\Tasks;

class Event extends Model
{
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_description',
        'event_date',
        'event_venue',
        'category_id',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(EventCategories::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function divisions()
    {
        return $this->hasMany(Division::class, 'event_id');
    }
    
    public function members()
    {
        return $this->hasManyThrough(
            EventDivMember::class,
            Division::class,
            'event_id',
            'division_id',
            'event_id',
            'division_id'
        );
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'event_id', 'event_id');
    }

    public function talents()
    {
        return $this->hasMany(Talent::class, 'event_id', 'event_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'event_venue', 'venue_id');
    }
    public function primaryVenue()
    {
        return $this->hasOne(Venue::class, 'event_id', 'event_id')
            ->where('is_primary', true);
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class, 'event_id', 'event_id');
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'event_id', 'event_id');
    }

    public function items()
    {
        return $this->hasMany(Barang::class, 'event_id', 'event_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meetings::class, 'event_id', 'event_id');
    }

    public function documents()
    {
        return $this->hasMany(Documents::class, 'event_id', 'event_id');
    }


}
