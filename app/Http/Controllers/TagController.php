<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Tag model importieren, um auf die Datenbank zugreifen zu können
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate the request data
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        // Create a new tag with the validated data
        $tag = Tag::create([
            'name' => $request->input('name'),
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id) //: \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'name' => ['required', 'max:255']
        ]);

        // Update the track with the validated data
        $tag = Tag::findOrFail($id);
        $tag->name = $request->input('name');

        $tag->save();

        // Redirect to the show view of the updated track
        //return to_route('tracks.show', $tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id) //: \Illuminate\Http\RedirectResponse
    {
        // Find the track and delete it
        $tag = Tag::findOrFail($id);
        $tag->delete();

        // Redirect to the index view of the tracks
        // return to_route('tracks.index');
    }
}
