<?php

namespace Tests\Feature\Http\Controllers\Api;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\BoardController
 */
final class BoardControllerTest extends TestCase
{
    #[Test]
    public function reorder_responds_with(): void
    {
        $response = $this->get(route('boards.reorder'));

        $response->assertOk();
    }
}
