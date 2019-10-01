<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestResponse;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenAPIValidation\PSR7\Exception\ValidationFailed;
use OpenAPIValidation\PSR7\OperationAddress;
use OpenAPIValidation\PSR7\ValidatorBuilder;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
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
     */
    protected function assertResponseCompliantForOpenApiSpec(
        TestResponse $testResponse,
        string $method,
        string $path
    ) {
        $validator = (new ValidatorBuilder())
            ->fromYamlFile(__DIR__. '/../ApiSpec/petstore-expanded.yaml')
            ->getResponseValidator()
        ;

        $operation = new OperationAddress($path, strtolower($method)) ;

        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $psrResponse = $psrHttpFactory->createResponse($testResponse->baseResponse);

        try {
            $validator->validate($operation, $psrResponse);
        } catch (ValidationFailed $validationFailed) {
            self::fail($validationFailed->getPrevious()->getMessage());
        }
    }

    /**
     * @param array $requestBody
     * @param string $method
     * @param string $uri
     */
    protected function assertRequestCompliantForOpenApiSpec(
        array $requestBody,
        string $method,
        string $uri
    ) {
        $validator = (new ValidatorBuilder())
            ->fromYamlFile(__DIR__. '/../ApiSpec/petstore-expanded.yaml')
            ->getRequestValidator()
        ;

        $psr17Factory = new Psr17Factory();
        $json = json_encode($requestBody, JSON_UNESCAPED_UNICODE);
        $stream = $psr17Factory->createStream($json);
        $stream->rewind();
        $request = $psr17Factory->createRequest(strtolower($method), $uri)
            ->withBody($stream)
            ->withHeader('Content-Type', 'application/json')
        ;

        try {
            $validator->validate($request);
        } catch (ValidationFailed $validationFailed) {
            self::fail($validationFailed->getPrevious()->getMessage());
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
        $this->assertRequestCompliantForOpenApiSpec($data, $method, $uri);

        $response = ($format === 'json')
            ? $this->postJson($uri, $data, $headers)
            : $this->post($uri, $data, $headers)
        ;
        $response->assertStatus($exceptStatusCode);

        $this->assertResponseCompliantForOpenApiSpec($response, $method, $schemaPath);
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
        $this->assertRequestCompliantForOpenApiSpec($data, $method, $uri);

        $response = ($format === 'json')
            ? $this->putJson($uri, $data, $headers)
            : $this->put($uri, $data, $headers)
        ;
        $response->assertStatus($exceptStatusCode);

        $this->assertResponseCompliantForOpenApiSpec($response, $method, $schemaPath);
        return $response;
    }
}
