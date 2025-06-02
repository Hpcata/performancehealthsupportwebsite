<?php
namespace App\Imports;

use App\Models\Item;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Log;

class ItemsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Skip header
        $rows->shift();

        foreach ($rows as $row) {
            try {
                $itemId = $row[0];
                $tagName = trim($row[1]);
                $currentTitle = trim($row[2]);
                $newTitle = trim($row[3]);

                // 1. Find item by id and title
                $item = Item::where('id', $itemId)
                            ->where('title', $currentTitle)
                            ->first();

                if ($item) {
                    // 2. Update title
                    $item->title = $newTitle;
                    $item->save();

                    // 3. Find or create tag
                    if($tagName != '' || $tagName != null){
                        $tag = Tag::firstOrCreate(['name' => $tagName]);
                        $item->tags()->syncWithoutDetaching([$tag->id]);
                    }   
                    Log::debug("✅ Updated item ID {$itemId}: {$newTitle}");

                }
                else {
                    
                    Log::debug("✅ Item ID {$itemId}: {$newTitle} not found.");
                }
            } catch (\Exception $e) {
                Log::error("Error for ID {$itemId} - {$newTitle}: " . $e->getMessage());
            }
        }
    }
}
