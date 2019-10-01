<?php

namespace Tests\Feature\Pet;

use Tests\Feature\OpenApiSpecAssertions;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use OpenApiSpecAssertions;

    /**
     * @test
     */
    public function destroySuccess()
    {
        $petId = rand(4, 9);
        $response = $this->delete("api/pets/{$petId}");

        $response->assertStatus(204);
        $this->assertResponseCompliantForOpenApiSpec(
            $response,
            'DELETE',
            '/pets/{id}',
            204
        );
    }
}
