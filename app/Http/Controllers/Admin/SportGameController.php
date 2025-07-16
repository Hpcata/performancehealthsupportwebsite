<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SportCategory;
use App\Models\SportGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class SportGameController extends Controller
{
    public function index() {
        try {
            $games = SportGame::with('categories')->get();
            return view('backend.pages.sport-games.index', compact('games'));
        } catch (Exception $e) {
            Log::error('Error loading sport games: ' . $e->getMessage());
            return back()->with('error', 'Failed to load sport games.');
        }
    }

    public function create() {
        try {
            $categories = SportCategory::all();
            return view('backend.pages.sport-games.form', compact('categories'));
        } catch (Exception $e) {
            Log::error('Error loading sport game form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load sport game creation form.');
        }
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'sport_category_id' => 'required|exists:sport_categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('sport_games', 'public');
            }

            $game = SportGame::create(['name' => $request->name]);

            $game->categories()->attach($request->sport_category_id, [
                'image_path' => $imagePath,
            ]);

            return redirect()->route('admin.sport-games.index')->with('success', 'Sport Game created.');
        } catch (Exception $e) {
            Log::error('Error creating sport game: ' . $e->getMessage());
            return back()->with('error', 'Failed to create sport game.')->withInput();
        }
    }

    public function edit($id) {
        try {
            $game = SportGame::with('categories')->findOrFail($id);
            $categories = SportCategory::all();
            return view('backend.pages.sport-games.form', compact('game', 'categories'));
        } catch (Exception $e) {
            Log::error('Error editing sport game: ' . $e->getMessage());
            return redirect()->route('admin.sport-games.index')->with('error', 'Failed to load sport game for editing.');
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'sport_category_id' => 'required|exists:sport_categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $game = SportGame::findOrFail($id);
            $game->update(['name' => $request->name]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $existing = $game->categories()->where('sport_category_id', $request->sport_category_id)->first();
                if ($existing && $existing->pivot->image_path && Storage::disk('public')->exists($existing->pivot->image_path)) {
                    Storage::disk('public')->delete($existing->pivot->image_path);
                }

                $imagePath = $request->file('image')->store('sport_games', 'public');
            } else {
                $existing = $game->categories()->where('sport_category_id', $request->sport_category_id)->first();
                $imagePath = $existing->pivot->image_path ?? null;
            }

            $game->categories()->sync([
                $request->sport_category_id => ['image_path' => $imagePath],
            ]);

            return redirect()->route('admin.sport-games.index')->with('success', 'Sport Game updated.');
        } catch (Exception $e) {
            Log::error('Error updating sport game: ' . $e->getMessage());
            return back()->with('error', 'Failed to update sport game.')->withInput();
        }
    }

    public function destroy($id) {
        try {
            $game = SportGame::findOrFail($id);

            // Delete associated image
            foreach ($game->categories as $category) {
                if ($category->pivot->image_path && Storage::disk('public')->exists($category->pivot->image_path)) {
                    Storage::disk('public')->delete($category->pivot->image_path);
                }
            }

            $game->delete();

            return redirect()->route('admin.sport-games.index')->with('success', 'Sport Game deleted.');
        } catch (Exception $e) {
            Log::error('Error deleting sport game: ' . $e->getMessage());
            return redirect()->route('admin.sport-games.index')->with('error', 'Failed to delete sport game.');
        }
    }
}
