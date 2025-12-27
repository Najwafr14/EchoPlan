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

    // App\Models\Event.php

    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'event_id', 'event_id');
    }

}
