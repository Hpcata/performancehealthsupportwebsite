<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\UrlService;
use App\Services\JsonService;

class ClubController extends Controller
{
    protected $urlService, $jsonService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->urlService = new UrlService;
        $this->jsonService = new JsonService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clubs = Club::all();
        return view('backend.pages.clubs.index', compact('clubs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.clubs.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'logo' => 'required|image|max:2048', // 2MB max
            ]);

            if ($request->hasFile('logo')) {
                $validatedData['logo'] = $request->file('logo')->store('clubs', 'public');
            }

            Club::create([
                'name' => $validated['name'] ?? null,
                'logo' => $validatedData['logo'] ?? null,
            ]);

            return redirect()->route('admin.clubs.index')->with('success', 'Club added successfully.');
        } catch (Exception $e) {
            Log::error(__METHOD__ . " {$e->getMessage()} ");
            return $this->jsonService->sendRequest(false, [], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $club = Club::find($id);
        return view('backend.pages.clubs.form', compact('club'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'logo' => 'nullable|image|max:2048',
            ]);

            $club = Club::find($id);
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('clubs', 'public');
                $club->logo = $path;
            }

            $club->name = $validated['name'] ?? $club->name;
            $club->save();

            return redirect()->route('admin.clubs.index')->with('success', 'Club updated successfully.');

        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Whoops! something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $club = Club::find($id);
            $club->delete();
            return redirect()->route('admin.clubs.index')->with('success', 'Club deleted successfully.');
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Whoops! something went wrong.');
        }
    }
}
