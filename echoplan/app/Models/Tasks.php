<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    // ⛔️ karena PK bukan "id"
    protected $primaryKey = 'task_id';

    // ⛔️ karena PK auto increment integer
    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'event_id',
        'division_id',
        'task_name',
        'description',
        'assigned_to',
        'status',
        'deadline',
    ];

    /**
     * =====================
     * RELATIONSHIPS
     * =====================
     */

    // Task → Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    // Task → Division
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'division_id');
    }

    // Task → User (assigned_to)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }

    // Task → Histories
    public function histories()
    {
        return $this->hasMany(TaskHistories::class, 'task_id', 'task_id');
    }
}
