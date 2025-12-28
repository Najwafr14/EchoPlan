<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $primaryKey = 'vendor_id';

    protected $fillable = [
        'event_id',
        'vendor_name',
        'vendor_service',
        'cost',
        'contact_info',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    // Relasi Many-to-One ke Events
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    // Relasi One-to-Many ke Barang
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'vendor_id', 'vendor_id');
    }
}