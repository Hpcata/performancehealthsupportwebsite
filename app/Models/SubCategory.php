<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title', 'description', 'image'];

    protected $table = 'subcategories';

    public function items()
    {
        return $this->belongsToMany(Item::class, 'subcategories_items');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
