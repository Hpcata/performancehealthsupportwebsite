<?php

namespace App\Http\Controllers\Front\Profile;
use App\Models\UserPlan;
use App\Services\UrlService;
use App\Services\JsonService;
use App\Http\Controllers\Controller;

class ResourceAndToolsController extends Controller
{
    protected $urlService, $jsonService;
    public function __construct()
    {
        $this->urlService = new UrlService;
        $this->jsonService = new JsonService;
    }

    public function index()
    {
        return view('front.pages.profile.resource-and-tools');
    }
}