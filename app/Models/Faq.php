<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{

   // Explicit table name if not standard (optional)
    protected $table = 'faqs';

    // Fillable fields for mass-assignment
    protected $fillable = ['question', 'answer'];
}
