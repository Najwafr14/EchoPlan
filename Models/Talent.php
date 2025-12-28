<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    use HasFactory;

    protected $table = 'talent';
    protected $primaryKey = 'talent_id';

    protected $fillable = [
        'event_id',
        'talent_name',
        'talent_type',
        'talent_fee',
        'performance_date',
        'contact_info',
    ];

    protected $casts = [
        'performance_date' => 'datetime',
        'talent_fee' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}