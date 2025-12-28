<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class EventCategories extends Model
{
    protected $table = 'eventcategories';
    protected $primaryKey = 'category_id';
    

    protected $fillable = [
        'category_name',
        'description',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }
}
