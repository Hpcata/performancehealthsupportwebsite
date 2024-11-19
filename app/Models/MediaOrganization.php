<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaOrganization extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'media_organization';

    public $timestamps = false;
}
