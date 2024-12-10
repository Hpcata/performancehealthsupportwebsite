<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $guarded = [];

    public function getMediaPathByMediaID($id) {
        $media = Media::find($id);
        $mediaImage = $media ? $media->path . '/' . $media->name : config('constant.DEFAULT_IMAGE_PATH');
        return Storage::disk('public')->url($mediaImage);
    }
}
