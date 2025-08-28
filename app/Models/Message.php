<?php

// app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'attachment',
        'attachment_type',
        'is_read',
        'read_at',
        'deleted_at',
    ];

    public function getMessageAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return '[Encrypted Message Error]'; // Sent to frontend
        }
    }

    public function getAttachmentAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return '[Encrypted Attachment Error]'; // Sent to frontend
        }
    }

    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setAttachmentAttribute($value)
    {
        $this->attributes['attachment'] = $value ? Crypt::encryptString($value) : null;
    }
}
