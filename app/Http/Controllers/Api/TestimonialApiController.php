<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class TestimonialApiController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('testimonialImage')->get();
        $result = $testimonials->map(function ($testimonial) {
            return [
                'id' => (string) $testimonial->id,
                'name' => $testimonial->name,
                'description' => $testimonial->review, // Assuming 'review' is the description
                'thumbnail_image' => $testimonial->testimonialImage
                    ? webAssets('/storage' .$testimonial->testimonialImage->path . '/' . $testimonial->testimonialImage->name)
                    : null,
            ];
        });
        return response()->json($result);
    }
} 