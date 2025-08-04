<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function index(Page $page)
    {
        $sections = $page->sections()->orderBy('order')->get();
        return view('backend.pages.sections.index', compact('page', 'sections'));
    }

    public function create(Request $request, Page $page)
    {
        $pages = Page::all();
        return view('backend.pages.sections.create', compact('pages', 'page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'section_type' => 'required|string|in:' . implode(',', array_keys(Section::getSectionTypes())) . '|unique:sections,section_type',
            'page_id' => 'required|exists:pages,id',
            'content' => 'nullable|string',
            'enabled' => 'required|boolean',
            'images.*' => 'nullable|image|max:2048',
            'banner_images.*' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
        ]);

        try {
            $content = $request->has('content') ? html_entity_decode($request->content) : null;

            // Handle image uploads (Dropzone multiple files)
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('sections/images', 'public');
                    $imagePaths[] = $path;
                }
            }

            $bannerPaths = [];
            if ($request->hasFile('banner_images')) {
                foreach ($request->file('banner_images') as $file) {
                    $path = $file->store('sections/banners', 'public');
                    $bannerPaths[] = $path;
                }
            }

            $lastOrder = Section::where('page_id', $request->page_id)->max('order');
            $request->merge(['order' => $lastOrder + 1]);

            // Save into DB
            $section = Section::create([
                'title' => $request->title,
                'section_type' => $request->section_type,
                'page_id' => $request->page_id,
                'content' => $content,
                'enabled' => $request->enabled,
                'order' => $request->order,
                'image' => $imagePaths,
                'banner_image' => $bannerPaths,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Section added successfully.',
                'section' => $section,
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding section: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add section.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Page $page, Section $section)
    {
        $pages = Page::all();
        $page = $section->page;
        return view('backend.pages.sections.edit', compact('section', 'pages', 'page'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required|string',
            'section_type' => 'required|string|in:' . implode(',', array_keys(Section::getSectionTypes())) . '|unique:sections,section_type,' . $section->id,
            'page_id' => 'required|exists:pages,id',
            'content' => 'nullable|string',
            'enabled' => 'required|boolean',
            'images.*' => 'nullable|image|max:2048',
            'banner_images.*' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
        ]);

        try {
            $content = $request->has('content') ? html_entity_decode($request->content) : null;
            $removedImages = $request->has('removed_images') ? $request->removed_images : [];
            $removedBannerImages = $request->has('removed_banner_images') ? $request->removed_banner_images : [];

            // Existing images (from hidden inputs or model)
            $existingImages = $section->image ?? [];
            $existingBannerImages = $section->banner_image ?? [];

            // New uploaded images
            $newImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $newImages[] = $file->store('sections/images', 'public');
                }
            }

            $newBannerImages = [];
            if ($request->hasFile('banner_images')) {
                foreach ($request->file('banner_images') as $file) {
                    $newBannerImages[] = $file->store('sections/banners', 'public');
                }
            }

            // Combine old and new (if needed)
            $allImages = array_merge($existingImages, $newImages);
            $allBannerImages = array_merge($existingBannerImages, $newBannerImages);

            if(!empty($removedImages)) {
                $allImages = array_diff($allImages, $removedImages);
                $allImages = array_values($allImages);
            }

            if(!empty($removedBannerImages)) {
                $allBannerImages = array_diff($allBannerImages, $removedBannerImages);
                $allBannerImages = array_values($allBannerImages);
            }

            // Update
            $section->update([
                'title' => $request->title,
                'section_type' => $request->section_type,
                'page_id' => $request->page_id,
                'content' => $content,
                'enabled' => $request->enabled,
                'image' => $allImages,
                'banner_image' => $allBannerImages,
            ]);

            return response()->json([
                'success' => true,
                'section' => $section,
                'message' => 'Section updated successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function destroy(Section $section)
    {
        $pageId = $section->page_id;
        $section->delete();
        return redirect()->route('sections.index', $pageId)->with('success', 'Section deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $orderedIds = $request->input('order');

        foreach ($orderedIds as $index => $id) {
            Section::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function getUsedSectionTypes()
    {
        $usedTypes = Section::whereNotNull('section_type')->pluck('section_type')->toArray();
        return response()->json(['used_types' => $usedTypes]);
    }
}
