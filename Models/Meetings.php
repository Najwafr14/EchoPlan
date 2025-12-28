<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetings extends Model
{
    use HasFactory;

    protected $table = 'meetings';
    protected $primaryKey = 'meeting_id';

    protected $fillable = [
        'event_id',
        'meeting_name',
        'meeting_date',
        'meeting_time',
        'meeting_place',
        'agenda',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}