<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    // Display a listing of the blogs.
    public function index()
    {
        $blogs = Blog::all();
        return view('backend.pages.blogs.index', compact('blogs'));
    }

    // Show the form for creating a new blog.
    public function create()
    {
        $tags = Tag::all();
        return view('backend.pages.blogs.create', compact('tags'));
    }

    // Store a newly created blog in storage.
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'description' => 'required',
            'author' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'is_published' => 'nullable|boolean',
        ]);

        $blog = new Blog($validated);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store the image with the original name in the public/backend/uploads/blog directory
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Generates a unique name
    
            // Set the destination path for the image
            $destinationPath = public_path('backend/uploads/blog');
            
            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);  // Create the directory if it doesn't exist
            }

            // Move the image to the desired directory
            $image->move($destinationPath, $imageName);
    
            // Update the blog's image field in the database
            $blog->image = 'backend/uploads/blog/' . $imageName;
        }
        $content = html_entity_decode($request->input('content')); // Get raw HTML from CKEditor
        $blog->content = $content;
        $blog->author = Auth::user()->id;
          // Save the blog to generate the blog ID
        $blog->save();

        // Handle tags
        if (!empty($validated['tags'])) {
            $tags = [];
            foreach ($validated['tags'] as $tagName) {
                // Find or create the tag and get its ID
                $tags[] = Tag::firstOrCreate(['name' => $tagName])->id;
            }

            // Attach tags to the blog (requires blog to be saved first)
            $blog->tags()->sync($tags);
        }

        return redirect()->route('backend.blogs.index')->with('success', 'Blog created successfully.');
    }

    // Display the specified blog.
    public function show(Blog $blog)
    {
        return true;
        // return view('backend.pages.blogs.show', compact('blog'));
    }

    // Show the form for editing the specified blog.
    public function edit(Blog $blog)
    {
        $tags = Tag::all();
        return view('backend.pages.blogs.edit', compact('blog', 'tags'));
    }

    // Update the specified blog in storage.
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'description' => 'required',
            'author' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string'
        ]);

        $blog->update($validated);

        if (!empty($validated['tags'])) {
            $tags = [];
            foreach ($validated['tags'] as $tagName) {
                $tags[] = Tag::firstOrCreate(['name' => $tagName])->id;
            }
            $blog->tags()->sync($tags);
        } else {
            $blog->tags()->detach();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store the image with the original name in the public/backend/uploads/blog directory
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Generates a unique name
    
            // Set the destination path for the image
            $destinationPath = public_path('backend/uploads/blog');
            
            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);  // Create the directory if it doesn't exist
            }

            // Move the image to the desired directory
            $image->move($destinationPath, $imageName);
    
            // Update the blog's image field in the database
            $blog->image = 'backend/uploads/blog/' . $imageName;
        }
        $content = html_entity_decode($request->input('content')); // Get raw HTML from CKEditor
        $blog->content = $content;
        $blog->author = Auth::user()->id;
        $blog->save();

        return redirect()->route('backend.blogs.index')->with('success', 'Blog updated successfully.');
    }

    // Remove the specified blog from storage.
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('backend.blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
