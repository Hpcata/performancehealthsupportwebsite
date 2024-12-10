<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('backend.pages.page.index', compact('pages'));
    }

    public function create()
    {
        return view('backend.pages.page.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages',
        ]);

        Page::create($request->only(['title', 'slug']));

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('backend.pages.page.form', compact('page'));
    }

    // public function show($slug)
    // {
    //     $page = Page::with(['sections' => function ($query) {
    //         $query->where('enabled', true)->orderBy('order');
    //     }])->where('slug', $slug)->firstOrFail();

    //     return view('pages.show', compact('page'));
    // }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug,' . $page->id,
        ]);

        $page->update($request->only(['title', 'slug']));

        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}
