<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usermeta extends Model
{
    protected $table = 'usermeta';
    protected $primaryKey = 'umeta_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['user_id', 'meta_key', 'meta_value'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
