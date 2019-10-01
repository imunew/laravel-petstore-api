<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestResponse;
use JsonSchema\Exception\ValidationException;
use JsonSchema\Validator;
use Tests\TestCase;

/**
 * Trait AssertResponseCompliantForSwaggerApiSpec
 * @package Tests\Feature
 *
 * @mixin TestCase
 */
trait OpenApiSpecAssertions
{
    /**
     * @param TestResponse $testResponse
     * @param string $method
     * @param string $path
     * @param int $exceptStatusCode
     */
    protected function assertResponseCompliantForOpenApiSpec(
        TestResponse $testResponse,
        string $method,
        string $path,
        int $exceptStatusCode
    ) {
        $validator = new Validator();

        $response = $testResponse->isEmpty()
            ? null
            : json_decode(json_encode($testResponse->json()))
        ;
        $apiSpec = json_decode(file_get_contents(__DIR__. '/../ApiSpec/petstore-expanded.json'));

        if (!property_exists($apiSpec, $path)) {
            self::fail(sprintf("[%s] The specified path is incorrect\n", $path));
        }

        if (!property_exists($apiSpec->{$path}, strtolower($method))) {
            self::fail(sprintf("[%s] Method is incorrect\n", $method));
        }

        if (!property_exists($apiSpec->{$path}->{strtolower($method)}->responses, $exceptStatusCode)) {
            self::fail(sprintf("[%s] Status code is incorrect\n", $exceptStatusCode));
        }
        $schema = $apiSpec->{$path}->{strtolower($method)}->responses->{$exceptStatusCode};
        $validator->validate($response, $schema);

        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                self::fail(sprintf("[%s] %s\n", $error['property'], $error['message']));
            }
        }
    }

    /**
     * @param array $requestBody
     * @param string $method
     * @param string $path
     */
    protected function assertRequestCompliantForOpenApiSpec(
        array $requestBody,
        string $method,
        string $path
    ) {
        $validator = new Validator();

        $apiSpec = json_decode(file_get_contents(__DIR__. '/../ApiSpec/petstore-expanded.json'));

        if (!property_exists($apiSpec, $path)) {
            self::fail(sprintf("[%s] The specified path is incorrect\n", $path));
        }

        if (!property_exists($apiSpec->{$path}, strtolower($method))) {
            self::fail(sprintf("[%s] Method is incorrect\n", $method));
        }

        $request = (object) $requestBody;
        $schema = optional($apiSpec->{$path}->{strtolower($method)})->body;
        if (empty($schema) && empty($requestBody)) {
            return;
        }

        try {
            $validator->validate($request, $schema, Validator::ERROR_ALL);
        } catch (ValidationException $validationException) {
            self::fail(implode(PHP_EOL, [
                "{$method} {$path} ". $validationException->getMessage(),
                json_encode($requestBody, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            ]));
        }
    }

    /**
     * @param string $schemaPath
     * @param int $exceptStatusCode
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param string $format
     * @return TestResponse
     */
    protected function validatePost(
        string $schemaPath,
        int $exceptStatusCode,
        string $uri,
        array $data = [],
        array $headers = [],
        string $format = 'json'
    ) {
        $method = 'POST';
        $this->assertRequestCompliantForOpenApiSpec($data, $method, $schemaPath);

        $response = ($format === 'json')
            ? $this->postJson($uri, $data, $headers)
            : $this->post($uri, $data, $headers)
        ;
        $response->assertStatus($exceptStatusCode);

        $this->assertResponseCompliantForOpenApiSpec($response, $method, $schemaPath, $exceptStatusCode);
        return $response;
    }

    /**
     * @param string $schemaPath
     * @param int $exceptStatusCode
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param string $format
     * @return TestResponse
     */
    protected function validatePut(
        string $schemaPath,
        int $exceptStatusCode,
        string $uri,
        array $data = [],
        array $headers = [],
        string $format = 'json'
    ) {
        $method = 'PUT';
        $this->assertRequestCompliantForOpenApiSpec($data, $method, $schemaPath);

        $response = ($format === 'json')
            ? $this->putJson($uri, $data, $headers)
            : $this->put($uri, $data, $headers)
        ;
        $response->assertStatus($exceptStatusCode);

        $this->assertResponseCompliantForOpenApiSpec($response, $method, $schemaPath, $exceptStatusCode);
        return $response;
    }
}
