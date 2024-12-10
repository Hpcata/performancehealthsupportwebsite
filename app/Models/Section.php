<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'page_id', 'type', 'enabled','content','order','image'];

    /**
     * Define the relationship with the Page model.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
