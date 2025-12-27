<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tasks;

class TaskHistories extends Model
{
    use HasFactory;

    // ⛔️ custom primary key
    protected $primaryKey = 'history_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'task_id',
        'old_status',
        'new_status',
        'note',
        'changed_by',
    ];

    /**
     * =====================
     * RELATIONSHIPS
     * =====================
     */

    // History → Task
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id', 'task_id');
    }

    // History → User (who changed it)
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by', 'user_id');
    }
}
