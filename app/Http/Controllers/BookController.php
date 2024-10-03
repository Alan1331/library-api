<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Retrieve a list of all books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve books from cache or database
        $books = Cache::remember('books', 60, function () {
            return Book::with('author')->get();
        });

        return response()->json($books);
    }

    /**
     * Retrieve details of a specific book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $book = Cache::remember("book_{$id}", 60, function () use ($id) {
            return Book::with('author')->findOrFail($id);
        });

        return response()->json([
            'id' => $book->id,
            'title' => $book->title,
            'description' => $book->description,
            'publish_date' => $book->publish_date->format('Y-m-d'),
            'author_id' => $book->author_id,
        ]);
    }

    /**
     * Create a new book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'publish_date' => 'required|date',
            'author_id' => 'required|exists:authors,id',
        ]);

        $book = Book::create($request->all());

        // Clear the cache
        Cache::forget('books');

        return response()->json($book, 201);
    }

    /**
     * Update an existing book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'author_id' => 'nullable|exists:authors,id',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        // Clear the cache for the specific book
        Cache::forget("book_{$id}");
        // Clear the general books cache
        Cache::forget('books');

        return response()->json($book);
    }

    /**
     * Delete a book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        // Clear the cache for the specific book
        Cache::forget("book_{$id}");
        // Clear the general books cache
        Cache::forget('books');

        return response()->json(null, 204);
    }
}
