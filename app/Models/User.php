<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
=======
use Laravel\Cashier\Billable;
>>>>>>> 23c30d7 (Escort project)


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
<<<<<<< HEAD
    use HasFactory, Notifiable;
=======
    use HasFactory, Notifiable, Billable;
>>>>>>> 23c30d7 (Escort project)

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
<<<<<<< HEAD
        'last_seen'
=======
        'last_seen',
        'username'
>>>>>>> 23c30d7 (Escort project)
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


    public function usermeta()
    {
        return $this->hasMany(Usermeta::class, 'user_id', 'id');
    }

    public function getMeta($key)
    {
        return $this->usermeta->where('meta_key', $key)->first()->meta_value ?? null;
    }

    // public function meta()
    // {
    //     return $this->hasOne(Usermeta::class, 'user_id');
    // }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Messages sent by this user
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Messages received by this user
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Calls initiated by this user
    public function initiatedCalls()
    {
        return $this->hasMany(CallHistory::class, 'caller_id');
    }

    // Calls received by this user
    public function receivedCalls()
    {
        return $this->hasMany(CallHistory::class, 'receiver_id');
    }

    // Relationships with other users
    public function relationships()
    {
        return $this->hasMany(UserRelationship::class, 'user_id');
    }

    // Notifications for this user
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

<<<<<<< HEAD
=======
    public function categories()
    {
        return $this->belongsToMany(EscortCategory::class, 'escort_category_user', 'user_id', 'escort_category_id');
    }

>>>>>>> 23c30d7 (Escort project)

}
