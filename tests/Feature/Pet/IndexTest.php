<?php

namespace Tests\Feature\Pet;

use Tests\Feature\OpenApiSpecAssertions;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use OpenApiSpecAssertions;

    /**
     * @test
     */
    public function indexSuccess()
    {
        $response = $this->get('api/pets');

        $response->assertOk();
        $this->assertResponseCompliantForOpenApiSpec(
            $response,
            'GET',
            '/pets'
        );
    }
}
