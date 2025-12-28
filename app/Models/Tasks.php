<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $primaryKey = 'task_id';

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
        'phase',
    ];


    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'division_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }

    public function histories()
    {
        return $this->hasMany(TaskHistories::class, 'task_id', 'task_id')
        ->orderBy('created_at', 'desc');
    }
}
