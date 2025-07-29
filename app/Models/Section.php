<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'page_id', 'enabled','content','order','image', 'banner_image'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'image' => 'array',
        'banner_image' => 'array',
    ];

    /**
     * Define the relationship with the Page model.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
