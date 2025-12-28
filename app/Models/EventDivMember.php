<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Division;
use App\Models\User;

class EventDivMember extends Model
{
    protected $table = 'event_divmembers';

    protected $primaryKey = 'member_id';

    protected $fillable = [
        'division_id',
        'user_id',
        'role_in_division'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
