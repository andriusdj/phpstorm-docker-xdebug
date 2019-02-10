<?php
declare(strict_types=1);

namespace Test\Unit\Api;

use AndriusJankevicius\Supermetrics\Api\Posts;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    /** @var MockObject */
    private $client;

    /** @var Posts */
    private $posts;

    protected function setUp()
    {
        $this->client = $this->createPartialMock(Client::class, ['get']);
        $this->posts = new Posts($this->client);
    }

    /**
     * @test
     */
    public function shouldRetrievePosts(): void
    {
        $this->client->method('get')
            ->willReturn(new Response(
                200,
                ['content-type' => 'application/json'],
                json_encode([
                    'posts' => [],
                    'page' => 2,
                ])
            ));

        $result = $this->posts->getPosts('test', 2);

        $this->assertSame([], $result);
    }

    /**
     * @test
     * @expectedException \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
     */
    public function shouldThrowExceptionsWhenInvalidDataReceived(): void
    {
        $this->client->method('get')
            ->willReturn(new Response(
                200,
                ['content-type' => 'application/json'],
                json_encode([
                    'posts' => [],
                    'page' => 6,
                ])
            ));

        $this->posts->getPosts('test', 2);
    }
}
