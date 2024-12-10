<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JsonService;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Models\SiteSettings;
use App\Models\Product;
use App\Models\Category;

class SiteSettingsController extends Controller
{
    protected $urlService, $jsonService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->urlService = new UrlService;
        $this->jsonService = new JsonService;
    }

    /**
     * Coupons list.
     *
     * @param \Illuminate\Http\Request
     *
     * @return void
     */
    public function index($slug)
    {
        // dd('233');
        try {
            if($slug == 'home') {
                $siteSettings = new SiteSettings;
                $settings = $siteSettings->getSettings($slug);

                // $category = new Category();
                // $parentChildCategory = $category->getParentChildCategory(true);
                // dd($parentChildCategory);

                return view('backend.pages.site-settings.home', ['settings' => $settings, 'parentChildCategory' => '']);
            } else if($slug == 'general') {
               
                $siteSettings = new SiteSettings;
                $settings = $siteSettings->getSettings($slug);
                // dd($settings);
                $pagesList = config('constant.PAGES_LIST');
                // dd($pagesList);
                return view('backend.pages.site-settings.general', ['pageList' => $pagesList, 'settings' => $settings]);
            } else {
                return redirect()->back()->withInput()->with('error', 'Invalid page.');
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Whoops! something went wrong.');
        }
    }

    public function saveSiteSettings(Request $request) {
        try {

            $this->validate($request, [
                'general.footer_site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'general.site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'home.banner.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $slug = $request->input('page');
            $section = $request->input('section');
            if($slug == 'home') {
                $data = $request->input($slug);
                $uploadedFiles = $request->file();

                if($uploadedFiles) {
                    if(in_array($section, ['header','banner','offer'])) {
                        $uploadedFiles = $uploadedFiles[$slug];
                        foreach($data[$section] as $key => $value) {
                            if($key == 'remove') continue;
                            $data[$section][$key]['image'] = isset($uploadedFiles[$section][$key]['image']) ? $uploadedFiles[$section][$key]['image'] : null;
                        }
                    }
                }

                $siteSettings = new SiteSettings;
                $siteSettings->saveSettings($slug, $data, $section);
                return redirect()->back()->with('success', 'Settings saved successfully.');
            } else if($slug == 'general') {
                $data = $request->input($slug);
                $uploadedFiles = $request->file();

                if($uploadedFiles) {
                    $uploadedFiles = $uploadedFiles[$slug];
                    if(isset($uploadedFiles['site_logo'])) {
                        $data['site_logo'] = $uploadedFiles['site_logo'];
                    }

                    if(isset($uploadedFiles['footer_site_logo'])) {
                        $data['footer_site_logo'] = $uploadedFiles['footer_site_logo'];
                    }
                }

                $siteSettings = new SiteSettings;
                $siteSettings->saveSettings($slug, $data, $section);
                return redirect()->back()->with('success', 'Settings saved successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Invalid page.');
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
