<?php
// app/Http/Controllers/ImageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ImageController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generateImageForm()
    {
        return view('image_form');
    }

    public function generateImage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1024',
        ]);

        $prompt = $request->prompt;

        // Check cache
        $cachedImage = Cache::get($prompt);
        if ($cachedImage) {
            return response()->json(['image_url' => $cachedImage]);
        }

        // Generate new image if not cached
        $imageUrl = $this->openAIService->generateImage($prompt);

        // Store in cache for 1 hour
        Cache::put($prompt, $imageUrl, 3600);

        return response()->json(['image_url' => $imageUrl]);
    }
}
