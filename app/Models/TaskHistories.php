<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tasks;

class TaskHistories extends Model
{
    use HasFactory;

    protected $table = 'taskhistories';
    protected $primaryKey = 'history_id';

    protected $fillable = [
        'task_id',
        'old_status',
        'new_status',
        'note',
        'changed_by',
        'document_path'
    ];

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id', 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by', 'user_id');
    }
}
