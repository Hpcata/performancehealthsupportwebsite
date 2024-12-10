<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Page;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Page $page)
    {
        $sections = $page->sections()->orderBy('order')->get();
        return view('backend.pages.sections.index', compact('page', 'sections'));
    }

    public function create(Request $request)
    {
        $pages = Page::all();
        $page = Page::find($request->page_id);
        return view('backend.pages.sections.create', compact('pages', 'page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'page_id' => 'required|exists:pages,id',
            'type' => 'required|string',
            'content' => 'nullable|string',
            'enabled' => 'required|boolean',
            'order' => 'required|integer',
            'image' => 'nullable|image|max:2048',

        ]);

        // Handle content to ensure it's stored as raw HTML
        $content = $request->has('content') ? html_entity_decode($request->content) : null;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('sections', 'public');
        }

        // Create the section
        Section::create([
            'title' => $request->title,
            'page_id' => $request->page_id,
            'type' => $request->type,
            'content' => $content,
            'enabled' => $request->enabled,
            'order' => $request->order,
            'image' => $image ?? null,
        ]);

        return redirect()->route('sections.index', $request->page_id)->with('success', 'Section added successfully.');
    }

    public function edit(Section $section)
    {
        $pages = Page::all();
        $page = $section->page;
        return view('backend.pages.sections.edit', compact('section', 'pages', 'page'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required|string',
            'page_id' => 'required|exists:pages,id',
            'type' => 'required|string',
            'content' => 'nullable|string',
            'enabled' => 'required|boolean',
            'order' => 'required|integer',
            'image' => 'nullable|image|max:2048',

        ]);

        // Handle content to ensure it's stored as raw HTML
        $content = $request->has('content') ? html_entity_decode($request->content) : null;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('sections', 'public');
        }
        // Update the section
        $section->update([
            'title' => $request->title,
            'page_id' => $request->page_id,
            'type' => $request->type,
            'content' => $content,
            'enabled' => $request->enabled,
            'order' => $request->order,
            'image' => $image ?? null,
        ]);

        return redirect()->route('sections.index', $request->page_id)->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $pageId = $section->page_id;
        $section->delete();
        return redirect()->route('sections.index', $pageId)->with('success', 'Section deleted successfully.');
    }

}
