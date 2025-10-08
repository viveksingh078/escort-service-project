<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
=======

>>>>>>> 23c30d7 (Escort project)
class SupportTicket extends Model
{
    use HasFactory;

<<<<<<< HEAD
=======
    // ✅ Define ENUM status constants
    const STATUS_OPEN = 'open';
    const STATUS_REPLIED = 'replied';
    const STATUS_CLOSED = 'closed';

>>>>>>> 23c30d7 (Escort project)
    protected $fillable = [
        'name',
        'email',
        'category',
        'message',
        'attachment',
        'status',
        'user_id'
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
<<<<<<< HEAD

=======
>>>>>>> 23c30d7 (Escort project)
