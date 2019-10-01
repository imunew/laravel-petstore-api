<?php

namespace Tests\Feature\Pet;

use Tests\Feature\OpenApiSpecAssertions;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use OpenApiSpecAssertions;

    /**
     * @test
     */
    public function showSuccess()
    {
        $petId = rand(4, 9);
        $response = $this->get("api/pets/{$petId}");

        $response->assertOk();
        $this->assertResponseCompliantForOpenApiSpec(
            $response,
            'GET',
            '/pets/{id}',
            200
        );
    }
}
