<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Comment;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\CommentController
 */
final class CommentControllerTest extends TestCase
{
    #[Test]
    public function index_responds_with(): void
    {
        $response = $this->get(route('comments.index'));

        $response->assertNoContent(201);
    }

    #[Test]
    public function store_responds_with(): void
    {
        $response = $this->post(route('comments.store'));

        $response->assertOk();
    }

    #[Test]
    public function update_responds_with(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->put(route('comments.update', $comment));

        $response->assertOk();
    }

    #[Test]
    public function destroy_responds_with(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comments.destroy', $comment));

        $response->assertNoContent();
    }
}
