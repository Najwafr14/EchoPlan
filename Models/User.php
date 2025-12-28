<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Event;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'domicile',
        'role',
        'status',
        'phone_number',
        'born_date',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'born_date' => 'date',
    ];

    public function events(){
        return $this->belongsToMany(Event::class, 'event_div_members', 'user_id', 'event_id')->withTimestamps();
    }

    public function isChiefOfCommittee($eventId)
    {
        return EventDivMember::whereHas('division', function($query) use ($eventId) {
            $query->where('event_id', $eventId)
                ->whereHas('divisionType', function($q) {
                    $q->where('type_name', 'Chief of Committee');
                });
        })
        ->where('user_id', $this->user_id)
        ->where('role_in_division', 'Leader')
        ->exists();
    }
}
