<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author; // Make sure to import the Author model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to retrieve a list of all books.
     *
     * @return void
     */
    public function test_can_retrieve_all_books()
    {
        // Create an author and some books
        $author = Author::factory()->create();
        Book::factory()->count(3)->create(['author_id' => $author->id]);

        $response = $this->get('/books');

        $response->assertStatus(200);
        $response->assertJsonCount(3); // Ensure we have 3 books
    }

    /**
     * Test to retrieve a single book.
     *
     * @return void
     */
    public function test_can_retrieve_a_single_book()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->get("/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $book->id,
            'title' => $book->title,
            'description' => $book->description,
            'publish_date' => $book->publish_date->format('Y-m-d'), // Format the date
            'author_id' => $book->author_id,
        ]);
    }

    /**
     * Test to retrieve a non-existing book.
     *
     * @return void
     */
    public function test_cannot_retrieve_nonexistent_book()
    {
        $response = $this->getJson('/books/999');

        $response->assertStatus(404);
    }

    /**
     * Test to create a new book.
     *
     * @return void
     */
    public function test_can_create_a_new_book()
    {
        $author = Author::factory()->create();

        $response = $this->post('/books', [
            'title' => 'New Book Title',
            'description' => 'A great new book description.',
            'publish_date' => '2024-10-03',
            'author_id' => $author->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', [
            'title' => 'New Book Title',
            'author_id' => $author->id,
        ]);
    }

    /**
     * Test to create a new book with invalid data.
     *
     * @return void
     */
    public function test_cannot_create_book_with_invalid_data()
    {
        $response = $this->postJson('/books', ['title' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test to update an existing book.
     *
     * @return void
     */
    public function test_can_update_a_book()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->put("/books/{$book->id}", [
            'title' => 'Updated Book Title',
            'description' => 'Updated description.',
            'publish_date' => '2024-11-01',
            'author_id' => $author->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book Title',
        ]);
    }

    /**
     * Test to update a non-existing book.
     *
     * @return void
     */
    public function test_cannot_update_to_nonexistent_book()
    {
        $response = $this->putJson('/books/999', ['title' => 'Updated Title']);

        $response->assertStatus(404);
    }

    /**
     * Test to delete a book.
     *
     * @return void
     */
    public function test_can_delete_a_book()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->delete("/books/{$book->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    /**
     * Test to delete a non-existing book.
     *
     * @return void
     */
    public function test_cannot_delete_nonexistent_book()
    {
        $response = $this->deleteJson('/books/999');

        $response->assertStatus(404);
    }
}
