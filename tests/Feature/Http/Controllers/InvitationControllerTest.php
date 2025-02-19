<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Board;
use App\Models\Invitation;
use App\Notification\BoardInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InvitationController
 */
final class InvitationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvitationController::class,
            'store',
            \App\Http\Requests\InvitationStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $email = fake()->safeEmail();
        $board = Board::factory()->create();

        Notification::fake();

        $response = $this->post(route('invitations.store'), [
            'email' => $email,
            'board_id' => $board->id,
        ]);

        $invitations = Invitation::query()
            ->where('email', $email)
            ->where('board_id', $board->id)
            ->get();
        $this->assertCount(1, $invitations);
        $invitation = $invitations->first();

        Notification::assertSentTo($board->invitation, BoardInvitation::class, function ($notification) use ($board) {
            return $notification->board->is($board);
        });
    }


    #[Test]
    public function create_behaves_as_expected(): void
    {
        $response = $this->get(route('invitations.create'));
    }


    #[Test]
    public function destroy_deletes(): void
    {
        $invitation = Invitation::factory()->create();
        $invitations = Invitation::factory()->count(3)->create();

        $response = $this->delete(route('invitations.destroy', $invitation));

        $this->assertModelMissing($invitation);
    }


    #[Test]
    public function resend_responds_with(): void
    {
        $invitation = Invitation::factory()->create();

        Notification::fake();

        $response = $this->get(route('invitations.resend'));

        $response->assertOk();
        $response->assertJson($Boards/Invitation/Index with:invitations);

        Notification::assertSentTo($board->invitation, BoardInvitation::class, function ($notification) use ($board) {
            return $notification->board->is($board);
        });
    }
}
