<?php

namespace Tests\Feature\Pet;

use PHPUnit\Framework\AssertionFailedError;
use Tests\Feature\OpenApiSpecAssertions;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use OpenApiSpecAssertions;

    /**
     * @test
     */
    public function storeSuccess()
    {
        $this->validatePost(
            '/pets',
            200,
            '/api/pets',
            [
                'name' => 'newPet',
            ]
        );
    }

    /**
     * @test
     * @dataProvider getInvalidPost
     * @param array $invalidPost
     */
    public function storeFailByInvalidPost(array $invalidPost)
    {
        $this->expectException(AssertionFailedError::class);
        $this->assertRequestCompliantForOpenApiSpec($invalidPost, 'POST', '/api/pets');
    }

    /**
     * @return array
     */
    public function getInvalidPost()
    {
        return [
            [[]],
            [['name' => null]],
            [['name' => 123]],
        ];
    }
}
