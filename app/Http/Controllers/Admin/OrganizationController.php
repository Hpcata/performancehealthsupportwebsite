<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Media;
use App\Models\Booking;
use App\Services\UrlService;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Services\JsonService;
use App\Models\MediaOrganization;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    protected $urlService, $jsonService, $fileService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->urlService = new UrlService;
        $this->jsonService = new JsonService;
        $this->fileService = new FileService;
    }

    public function index() {
        return view('backend.pages.organization.edit');
    }

    public function mediaUpload(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'files.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Max file size 2MB (2048 KB)
                'files.*' => function ($attribute, $value, $fail) {
                    // Get the image dimensions
                    list($width, $height) = getimagesize($value);
                    
                    if ($width > 2000 || $height > 2000) {
                        $fail('The ' . $attribute . ' dimensions should not exceed 2000px.');
                    }
                },
            ]);
    
            $userObj = User::find($request->id);
            $files = $request->file('files');
            $mediaData = [];
    
            if (count($files)) {
                foreach ($files as $_file) {
                    $media = $this->fileService->uploadFile($_file);
                    $mediaData[] = [
                        'media_id' => $media->id,
                        'position' => $request->position,
                    ];
                }
            }
    
            $userObj->media()->attach($mediaData);
            return $this->jsonService->sendRequest(true, [], 200);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return $this->jsonService->sendRequest(false, [], 209);
        }
    }

    /**
     * get Product images.
     *
     * @param \Illuminate\Http\Request
     *
     * @return json
     */
    public function getImageList(Request $request)
    {
        try {
            $userObj = User::find($request->id);
            $imageList = $userObj->media()->where('position', $request->position)->orderBy('sort_order', 'ASC')->get();

            $data = [];
            foreach ($imageList as $key => $_list) {
                $data[] = [
                    'media_id' => $_list->id,
                    'path' => Storage::disk('public')->url($_list->path . '/' . $_list->name),
                ];
            }
            return $this->jsonService->sendRequest(true, $data, 200);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return $this->jsonService->sendRequest(false, [], 209);
        }
    }

    /**
     * get Product images.
     *
     * @param \Illuminate\Http\Request
     *
     * @return json
     */
    public function imageDelete(Request $request)
    {
        try {
            $media = Media::find($request->media_id);
            $userObj = Auth::user();
            $this->fileService->delete($media);
            $userObj->media()->detach($media->id);
            $media->delete();
            return $this->jsonService->sendRequest(true, [], 200);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return $this->jsonService->sendRequest(false, [], 209);
        }
    }

    /**
     * set Product sort order.
     *
     * @param \Illuminate\Http\Request
     *
     * @return json
     */
    public function sorting(Request $request)
    {
        try {
            $userObjId = $request->id;
            $mediaIds = $request->mediaIds;

            // echo '<pre>';
            // print_r($request->all());
            // die;

            if (count($mediaIds)) {
                foreach ($mediaIds as $key => $id) {
                    MediaOrganization::where('media_id', $id)->where('user_id', $userObjId)->update(['sort_order' => $key]);
                }
            }
            return $this->jsonService->sendRequest(true, [], 200);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return $this->jsonService->sendRequest(false, [], 209);
        }
    }
}