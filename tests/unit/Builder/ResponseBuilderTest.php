<?php

namespace Test\Builder;

use CliFyi\Builder\ResponseBuilder;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;

class ResponseBuilderTest extends TestCase
{
    /** @var ResponseBuilder */
    private $responseBuilder;

    protected function setUp()
    {
        parent::setUp();

        $this->responseBuilder = new ResponseBuilder();
    }

    public function testBuildResponse()
    {
        /** @var Response|Mockery\MockInterface $response */
        $response = Mockery::mock(Response::class);

        $response->shouldReceive('withHeader')
            ->withArgs(['key', 'value'])
            ->andReturn($response);

        $response->shouldReceive('withJson')
            ->withArgs([
                ['some' => 'data'],
                200,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS
            ])
            ->andReturn($response);

        $builtResponse = $this->responseBuilder
            ->withResponse($response)
            ->withHeader('key', 'value')
            ->withJsonArray(['some' => 'data'])
            ->getBuiltResponse();

        $this->assertSame($response, $builtResponse);
    }
}
