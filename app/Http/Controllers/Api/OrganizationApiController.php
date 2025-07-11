<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class OrganizationApiController extends Controller
{
    public function images()
    {
        // You may want to make this dynamic, but for now, get the first admin user
        // $admin = User::where('is_superadmin', 1)->first();
        // if (!$admin) {
        //     return response()->json(["error" => "No admin found"], 404);
        // }
        $organization = getOrganizationImages();
        return response()->json($organization);
    }
} 