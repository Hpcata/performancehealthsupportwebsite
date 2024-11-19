<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Log;

class FileService
{
    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('public');
    }

    public function uploadFile($file)
    {
        try {
            $label = $path = $mediaType = '';
            $extension = $file->getClientOriginalExtension();
            if (in_array($extension, config('constant.IMG_EXTENTIONS'))) {
                $label = 'IMG';
                $path = config('constant.IMAGE_PATH');
                $mediaType = 'image';
            } elseif (in_array($extension, config('constant.VID_EXTENTIONS'))) {
                $label = 'VID';
                $path = config('constant.VIDEO_PATH');
                $mediaType = 'video';
            } else {
                $label = 'FILE';
                $path = config('constant.FILE_PATH');
                $mediaType = 'file';
            }
            $fileName = $label . '-' . rand(10000, 99999) . '.' . $extension;
            $this->storage->put($path . '/' . $fileName, file_get_contents($file));

            $media = Media::create([
                'name' => $fileName,
                'path' => $path,
                'media_type' => $mediaType,
                'extension' => $extension,
                'size' => $file->getSize(),
            ]);
            Log::info('File uploaded successfully');
            return $media;
        } catch (Exception $e) {
            Log::error($e->gerMessage());
        }
    }

    public function delete($media)
    {
        try {
            $this->storage->delete($media->path . '/' . $media->name);
            Log::info('File deleted successfully');
        } catch (Exception $e) {
            Log::error($e->gerMessage());
        }
    }
}
