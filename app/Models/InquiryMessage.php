<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryMessage extends Model
{
    use HasFactory;

    protected $table = 'inquiry_message';

    // Define which attributes can be mass assigned
    protected $fillable = [
        'user_id',
        'query_id',
        'subject',
        'message',
        'status',
        'created_at',
        'updated_at',
    ];
}
