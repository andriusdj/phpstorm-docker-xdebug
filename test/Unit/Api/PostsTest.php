<?php
declare(strict_types=1);

namespace Test\Unit\Api;

use AndriusJankevicius\Supermetrics\Api\Posts;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
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
                json_encode(['data' => [
                    'posts' => [],
                    'page' => 2,
                ]])
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
                json_encode(['data' => [
                    'posts' => [],
                    'page' => 6,
                ]])
            ));

        $this->posts->getPosts('test', 2);
    }

    /**
     * @param int $page
     *
     * @return array
     * @throws InvalidApiResponseException
     */
    public static function postsFromApiTestSample(int $page): array
    {
        $pages = [
            1 => [
                0 =>
                    [
                        'id' => 'post5c63cd7eb65ef_15f2b217',
                        'from_name' => 'Mandie Nagao',
                        'from_id' => 'user_17',
                        'message' => '1234',
                        'type' => 'status',
                        'created_time' => '2019-01-05T20:46:14+00:00',
                    ]
                ,
                1 =>
                    [
                        'id' => 'post5c63cd7eb65fd_6f41ee3f',
                        'from_name' => 'Lael Vassel',
                        'from_id' => 'user_6',
                        'message' => '12345',
                        'type' => 'status',
                        'created_time' => '2019-01-15T17:38:46+00:00',
                    ]
                ,
            ],
            2 => [
                2 =>
                    [
                        'id' => 'post5c63cd7eb6607_471cd924',
                        'from_name' => 'Lael Vassel',
                        'from_id' => 'user_6',
                        'message' => '123',
                        'type' => 'status',
                        'created_time' => '2019-01-25T13:12:46+00:00',
                    ]
                ,
                3 =>
                    [
                        'id' => 'post5c63cd7eb6612_d6d81505',
                        'from_name' => 'Lashanda Small',
                        'from_id' => 'user_12',
                        'message' => '1234',
                        'type' => 'status',
                        'created_time' => '2019-01-25T07:58:38+00:00',
                    ]
                ,
            ],
            3 => [
                4 =>
                    [
                        'id' => 'post5c63cd7eb65ef_15f2b217',
                        'from_name' => 'Mandie Nagao',
                        'from_id' => 'user_17',
                        'message' => '1234567',
                        'type' => 'status',
                        'created_time' => '2019-03-15T20:46:14+00:00',
                    ]
                ,
                5 =>
                    [
                        'id' => 'post5c63cd7eb65fd_6f41ee3f',
                        'from_name' => 'Carly Alvarez',
                        'from_id' => 'user_6',
                        'message' => '12345678',
                        'type' => 'status',
                        'created_time' => '2019-03-15T17:38:46+00:00',
                    ]
                ,
            ],
            4 => [
                6 =>
                    [
                        'id' => 'post5c63cd7eb6607_471cd924',
                        'from_name' => 'Lael Vassel',
                        'from_id' => 'user_0',
                        'message' => '1234',
                        'type' => 'status',
                        'created_time' => '2019-03-25T13:12:46+00:00',
                    ]
                ,
                7 =>
                    [
                        'id' => 'post5c63cd7eb6612_d6d81505',
                        'from_name' => 'Lashanda Small',
                        'from_id' => 'user_12',
                        'message' => '123456789',
                        'type' => 'status',
                        'created_time' => '2019-03-25T07:58:38+00:00',
                    ]
                ,
            ],
        ];

        if (!isset($pages[$page])) {

            throw new InvalidApiResponseException('Wrong posts page returned');
        }

        return $pages[$page];
    }
}
