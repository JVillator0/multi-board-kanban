<?php

namespace Tests\Feature\Http\Controllers\Api;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\InvitationController
 */
final class InvitationControllerTest extends TestCase
{
    #[Test]
    public function index_responds_with(): void
    {
        $response = $this->get(route('invitations.index'));

        $response->assertOk();
    }


    #[Test]
    public function store_responds_with(): void
    {
        $response = $this->post(route('invitations.store'));

        $response->assertNoContent(201);
    }


    #[Test]
    public function resend_responds_with(): void
    {
        $response = $this->get(route('invitations.resend'));

        $response->assertOk();
    }


    #[Test]
    public function revoke_responds_with(): void
    {
        $response = $this->get(route('invitations.revoke'));

        $response->assertNoContent();
    }
}
