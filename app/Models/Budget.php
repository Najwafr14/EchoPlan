<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $primaryKey = 'budget_id';
    protected $table = 'budget';
    
    protected $fillable = [
        'event_id',
        'transaction_type',
        'budget_type',
        'budget_item',
        'amount',
        'payment_method',
        'payment_date',
        'status',
        'receipt_path',
        'notes',
        'approved_by',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function scopeIncome($query)
    {
        return $query->where('transaction_type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('transaction_type', 'expense');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'Paid');
    }
}