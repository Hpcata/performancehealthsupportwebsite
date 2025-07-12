<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionElement extends Model
{
    use HasFactory;

    protected $table = 'section_elements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['section_element_name', 'description', 'created_at', 'updated_at'];
}
