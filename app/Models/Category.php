<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Subset;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id', 'title', 'description', 'image'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
