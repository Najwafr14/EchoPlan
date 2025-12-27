<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meetings extends Model
{
    protected $table = 'meetings';
    protected $primaryKey = 'meeting_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'event_id',
        'meeting_name',
        'meeting_date',
        'meeting_time',
        'meeting_place',
        'agenda',
        'notes',
    ];
}
