<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Testimonial;
use App\Services\UrlService;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Services\JsonService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TestimonialRequest;

class TestimonialController extends Controller
{
    protected $testimonial, $urlService, $fileService, $jsonService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->testimonial = new Testimonial;
        $this->urlService = new UrlService;
        $this->fileService = new FileService;
        $this->jsonService = new JsonService;
    }

    public function index()
    {
        return view('backend.pages.testimonial.list');
    }

    /**
     * Testimonial list ajax to get testimonials list.
     *
     * @param \Illuminate\Http\Request
     */
    public function listAjax(Request $request)
    {
        try {
            $response = Testimonial::all();

            $response = Testimonial::leftJoin('media', 'testimonials.testimonial_image', 'media.id')
                ->where('testimonials.user_id', auth()->user()->id)
                ->get([
                    'testimonials.id',
                    'testimonials.name',
                    'testimonials.designation',
                    'testimonials.review',
                    'media.name as media_name',
                    'media.path',
                ]);

            if (isset($response) && count($response)) {
                foreach ($response as $key => $_response) {
                    $testimonialImage = $_response->media_name ? $_response->path . '/' . $_response->media_name : config('constant.DEFAULT_IMAGE_PATH');
                    $response[$key]['testimonial_image'] = env('APP_URL') . '/v2/storage/app/public/' . ($testimonialImage);
                    $response[$key]['action'] = '<a href="' . route('testimonials.edit', $this->urlService->encodeId($_response->id)) . '" class="btn btn-outline-secondary"><i class="icofont-edit text-success"></i></a>&nbsp;<a class="btn btn-outline-secondary deleterow" data-id=' . $this->urlService->encodeId($_response->id) . '><i class="icofont-ui-delete text-danger"></i></a>';
                }
            }

            return $this->jsonService->sendRequest(true, $response, 200);
        } catch (Exception $e) {
            Log::error(__METHOD__ . " {$e->getMessage()} ");
            return $this->jsonService->sendRequest(false, [], 400);
        }
    }

    /**
     * Testimonial add form.
     *
     * @param \Illuminate\Http\Request
     *
     */
    public function add()
    {
        $testimonial = new Testimonial;
        return $this->_update($testimonial);
    }

    /**
     * Testimonial edit form.
     *
     * @param \Illuminate\Http\Request
     *
     */
    public function edit(Request $request)
    {
        $id = $this->urlService->decodeId($request->id);
        $testimonial = Testimonial::find($id);

        if(!$testimonial) {
            abort(404);
        }

        return $this->_update($testimonial);
    }

    /**
     * Testimonial update.
     *
     * @param \App\Models\Testimonial
     *
     */
    private function _update(Testimonial $testimonial)
    {
        $testimonialImage = $testimonial->testimonialImage ? env('APP_URL') . '/v2/storage/app/public/' . ($testimonial->testimonialImage->path . '/' . $testimonial->testimonialImage->name) : null;
        return view('backend.pages.testimonial.edit', compact('testimonial', 'testimonialImage'));
    }

    /**
     * Testimonial save after add or update.
     *
     * @param \App\Http\Requests\TestimonialRequest
     *
     */
    public function save(TestimonialRequest $request)
    {
        try {
            $id = $request->id;
            $postData = $request->except('_token', 'proengsoft_jsvalidation');

            $testimonialFile = $request->file('testimonial_image');

            DB::beginTransaction();

            if ($testimonialFile) {
                if ($id) {
                    $oldTestimonialImage = Testimonial::find($id);
                    if ($oldTestimonialImage->testimonialImage) {
                        $this->fileService->delete($oldTestimonialImage->testimonialImage);
                        $oldTestimonialImage->testimonialImage->delete();
                    }
                }
                $image = $this->fileService->uploadFile($testimonialFile);
                $postData['testimonial_image'] = $image->id;
            }

            $postData['user_id'] = auth()->user()->id;
            Testimonial::updateOrCreate(
                ['id' => $id],
                $postData
            );

            DB::commit();

            $msg = $id ? 'Testimonial updated successfully!' : 'Testimonial added successfully!';
            return redirect()->route('testimonials.index')->with('success', $msg);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Whoops! something went wrong.');
        }
    }

    /**
     * Testimonial delete.
     *
     * @param \Illuminate\Http\Request
     *
     * @return json
     */
    public function delete(Request $request)
    {
        try {
            $testimonial = Testimonial::where('id', $this->urlService->decodeId($request->id));
            $testimonial->delete();
            return $this->jsonService->sendRequest(true, [], 200);
        } catch (Exception $e) {
            Log::error(__METHOD__ . " {$e->getMessage()} ");
            return $this->jsonService->sendRequest(true, [], 400);
        }
    }

}
