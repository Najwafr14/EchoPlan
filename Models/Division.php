<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\DivisionType;
use App\Models\EventDivMember;

class Division extends Model
{
    protected $primaryKey = 'division_id';

    protected $fillable = [
        'event_id',
        'division_type_id',
        'division_name',
    ];

    public function event(){
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function divisionType(){
        return $this->belongsTo(DivisionType::class, 'division_type_id');
    }

    public function members(){
        return $this->hasMany(EventDivMember::class, 'division_id', 'division_id');
    }
}
