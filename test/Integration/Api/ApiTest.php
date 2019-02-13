<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 10/02/19
 * Time: 14:39
 */

namespace Test\Integration\Api;


use AndriusJankevicius\Supermetrics\Api\Posts;
use AndriusJankevicius\Supermetrics\Api\Registration;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /** @var Client */
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @test
     * @return string
     * @throws InvalidApiResponseException
     */
    public function shouldReceiveNewToken(): string
    {
        $registration = new Registration($this->client);

        $token = $registration->getToken();

        $this->assertNotEmpty($token);

        return $token;
    }

    /**
     * @test
     * @param string $token
     * @throws InvalidApiResponseException
     * @depends shouldReceiveNewToken
     */
    public function shouldReceiveFirstPageOfPosts(string $token): void
    {
        $posts = new Posts($this->client);

        $result = $posts->getPosts($token, 1);

        $this->assertNotEmpty($result);
        $this->assertCount(100, $result);
    }
}
