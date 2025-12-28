<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'event_id',
        'item_name',
        'item_type',
        'quantity',
        'item_status',
        'vendor_id',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relasi Many-to-One ke Events
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    // Relasi Many-to-One ke Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }
}