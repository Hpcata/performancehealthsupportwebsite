<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TestimonialApiController extends Controller
{
    public function index()
    {
        $admin = User::where('is_superadmin', 1)->first();
        if (!$admin) {
            return response()->json(["error" => "No admin found"], 404);
        }

        $testimonials = $admin->getTestimonials();
        return response()->json($testimonials);
    }
} 