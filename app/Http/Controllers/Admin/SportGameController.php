<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SportCategory;
use App\Models\SportGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SportGameController extends Controller
{
    public function index() {
        $games = SportGame::with('categories')->get();
        return view('backend.pages.sport-games.index', compact('games'));
    }

    public function create() {
        $categories = SportCategory::all();
        return view('backend.pages.sport-games.form', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'sport_category_id' => 'required|exists:sport_categories,id',
            'image' => 'nullable|image|dimensions:width=300,height=200|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sport_games', 'public');
        }

        $game = SportGame::create([
            'name' => $request->name,
        ]);

        $game->categories()->attach($request->sport_category_id, [
            'image_path' => $imagePath,
        ]);
        
        return redirect()->route('admin.sport-games.index')->with('success', 'Sport Game created.');
    }

     public function edit($id)
    {
        $game = SportGame::with('categories')->findOrFail($id);
        $categories = SportCategory::all();
        return view('backend.pages.sport-games.form', compact('game', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'sport_category_id' => 'required|exists:sport_categories,id',
            'image' => 'nullable|image|dimensions:width=300,height=200|max:2048',
        ]);

        $game = SportGame::findOrFail($id);
        $game->update(['name' => $request->name]);

        $imagePath = null;
       
        if ($request->hasFile('image')) {
            // Delete old if exists
            $existing = $game->categories()->where('sport_category_id', $request->sport_category_id)->first();
            if ($existing && $existing->pivot->image_path && Storage::disk('public')->exists($existing->pivot->image_path)) {
                Storage::disk('public')->delete($existing->pivot->image_path);
            }

            $imagePath = $request->file('image')->store('sport_games', 'public');
        } else {
            $existing = $game->categories()->where('sport_category_id', $request->sport_category_id)->first();
            $imagePath = $existing->pivot->image_path ?? null;
        }

        // Sync single category
        $game->categories()->sync([
            $request->sport_category_id => ['image_path' => $imagePath],
        ]);

        return redirect()->route('admin.sport-games.index')->with('success', 'Sport Game updated.');
    }

    public function destroy($id)
    {
        $game = SportGame::findOrFail($id);

        // Delete associated image
        if ($game->image_path && Storage::disk('public')->exists($game->image_path)) {
            Storage::disk('public')->delete($game->image_path);
        }

        $game->delete();

        return redirect()->route('admin.sport-games.index')->with('success', 'Sport Game deleted.');
    }
}
