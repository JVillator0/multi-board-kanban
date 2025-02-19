<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
final class CommentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'store',
            \App\Http\Requests\CommentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $content = fake()->paragraphs(3, true);
        $task = Task::factory()->create();

        $response = $this->post(route('comments.store'), [
            'content' => $content,
            'task_id' => $task->id,
        ]);

        $comments = Comment::query()
            ->where('content', $content)
            ->where('task_id', $task->id)
            ->get();
        $this->assertCount(1, $comments);
        $comment = $comments->first();
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'update',
            \App\Http\Requests\CommentUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $comment = Comment::factory()->create();
        $content = fake()->paragraphs(3, true);

        $response = $this->put(route('comments.update', $comment), [
            'content' => $content,
        ]);

        $comment->refresh();

        $this->assertEquals($content, $comment->content);
    }

    #[Test]
    public function destroy_deletes(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comments.destroy', $comment));

        $this->assertModelMissing($comment);
    }
}
