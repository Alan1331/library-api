<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    /**
     * Display a listing of all authors.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Check if cached, otherwise retrieve from DB and cache the result
        $authors = Cache::remember('authors', 60 * 60, function () {
            return Author::all();
        });

        return response()->json($authors);
    }

    /**
     * Display the specified author.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Check cache, otherwise retrieve author from DB
        $author = Cache::remember("author_{$id}", 60 * 60, function () use ($id) {
            return Author::findOrFail($id);
        });

        return response()->json([
            'id' => $author->id,
            'name' => $author->name,
            'bio' => $author->bio,
            'birth_date' => $author->birth_date->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created author in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string',
            'bio' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        // Create the author
        $author = Author::create($validated);

        // Clear the cache for the list of authors
        Cache::forget('authors');

        return response()->json($author, 201); // 201 Created
    }

    /**
     * Update the specified author in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'nullable|string',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        // Find the author or return 404
        $author = Author::findOrFail($id);

        // Update the author
        $author->update($validated);

        // Clear caches
        Cache::forget('authors');
        Cache::forget("author_{$id}");

        return response()->json($author);
    }

    /**
     * Remove the specified author from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the author or return 404
        $author = Author::findOrFail($id);

        // Delete the author
        $author->delete();

        // Clear caches
        Cache::forget('authors');
        Cache::forget("author_{$id}");

        return response()->json(['message' => 'Author deleted successfully']);
    }

    /**
     * Get all books by a specific author.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function books($id)
    {
        // Retrieve the author with the given ID and load the related books
        $author = Cache::remember("author_books_{$id}", 60, function () use ($id) {
            return Author::with('books')->findOrFail($id);
        });

        return response()->json(['books' => $author->books]);
    }
}
