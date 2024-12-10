<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'image'];

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'subcategories_items');
    }
}
