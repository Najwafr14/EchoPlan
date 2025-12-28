<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivisionType extends Model
{
    protected $table = 'divisiontypes';

    protected $primaryKey = 'division_type_id';

    protected $fillable = [
        'type_name',
        'description',
    ];

    public function divisions()
    {
        return $this->hasMany(Division::class, 'division_type_id', 'division_type_id');
    }
}
