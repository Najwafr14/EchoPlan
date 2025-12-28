<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budget';
    protected $primaryKey = 'budget_id';

    protected $fillable = [
        'event_id',
        'budget_type',
        'budget_item',
        'amount',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
