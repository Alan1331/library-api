<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase; // Refresh the database between tests

    /**
     * Test retrieving all authors.
     *
     * @return void
     */
    public function test_can_retrieve_all_authors()
    {
        // Create some authors in the database
        Author::factory()->count(3)->create();

        // Send a GET request to the /authors endpoint
        $response = $this->get('/authors');

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains 3 authors
        $response->assertJsonCount(3);
    }

    /**
     * Test retrieving a single author.
     *
     * @return void
     */
    public function test_can_retrieve_a_single_author()
    {
        // Create an author in the database
        $author = Author::factory()->create();

        // Send a GET request to retrieve the author
        $response = $this->get("/authors/{$author->id}");

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains the author's data
        $response->assertJson([
            'id' => $author->id,
            'name' => $author->name,
            'bio' => $author->bio,
            'birth_date' => $author->birth_date->toDateString(),
        ]);
    }

    /**
     * Test retrieving a non-existing author.
     *
     * @return void
     */
    public function test_cannot_retrieve_nonexistent_author()
    {
        $response = $this->getJson('/authors/999');

        $response->assertStatus(404);
    }

    /**
     * Test creating a new author.
     *
     * @return void
     */
    public function test_can_create_an_author()
    {
        // Create author data
        $authorData = [
            'name' => 'John Doe',
            'bio' => 'A famous author',
            'birth_date' => '1980-01-01',
        ];

        // Send a POST request to create a new author
        $response = $this->post('/authors', $authorData);

        // Assert that the response status is 201 (Created)
        $response->assertStatus(201);

        // Assert that the database contains the new author
        $this->assertDatabaseHas('authors', $authorData);
    }

    /**
     * Test creating a new author with invalid data.
     *
     * @return void
     */
    public function test_cannot_create_author_with_invalid_data()
    {
        $response = $this->postJson('/authors', ['name' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test updating an existing author.
     *
     * @return void
     */
    public function test_can_update_an_author()
    {
        // Create an author in the database
        $author = Author::factory()->create();

        // Updated data for the author
        $updatedData = [
            'name' => 'Jane Doe',
            'bio' => 'Updated bio',
            'birth_date' => '1990-05-15',
        ];

        // Send a PUT request to update the author
        $response = $this->put("/authors/{$author->id}", $updatedData);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the database contains the updated author data
        $this->assertDatabaseHas('authors', $updatedData);
    }

    /**
     * Test updating a non-existing author.
     *
     * @return void
     */
    public function test_cannot_update_to_nonexistent_author()
    {
        $response = $this->putJson('/authors/999', ['name' => 'Updated Name']);

        $response->assertStatus(404);
    }

    /**
     * Test deleting an author.
     *
     * @return void
     */
    public function test_can_delete_an_author()
    {
        // Create an author in the database
        $author = Author::factory()->create();

        // Send a DELETE request to remove the author
        $response = $this->delete("/authors/{$author->id}");

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the database no longer contains the author
        $this->assertDatabaseMissing('authors', [
            'id' => $author->id,
        ]);
    }

    /**
     * Test deleting a non-existing author.
     *
     * @return void
     */
    public function test_cannot_delete_nonexistent_author()
    {
        $response = $this->deleteJson('/authors/999');

        $response->assertStatus(404);
    }

    /**
     * Test to retrieve all books by a specific author.
     *
     * @return void
     */
    public function test_can_retrieve_all_books_by_author()
    {
        // Create an author
        $author = Author::factory()->create();

        // Create books associated with the author
        $books = Book::factory()->count(3)->create(['author_id' => $author->id]);

        // Send a GET request to the endpoint
        $response = $this->getJson("/authors/{$author->id}/books");

        // Assert the response status
        $response->assertStatus(200);

        // Assert that the response contains the books
        $response->assertJsonFragment(['id' => $books[0]->id]);
        $response->assertJsonFragment(['id' => $books[1]->id]);
        $response->assertJsonFragment(['id' => $books[2]->id]);
    }

    /**
     * Test to retrieve all books by a non-existing author.
     *
     * @return void
     */
    public function test_cannot_retrieve_books_for_nonexistent_author()
    {
        $response = $this->getJson('/authors/999/books');

        $response->assertStatus(404);
    }
}
