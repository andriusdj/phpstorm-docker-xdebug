<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 09/02/19
 * Time: 23:57
 */

namespace Test\Unit\Api;


use AndriusJankevicius\Supermetrics\Api\Registration;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * @var Registration
     */
    private $registration;
    /**
     * @var MockObject|Client
     */
    private $client;

    protected function setUp(): void
    {
        $this->client = $this->createPartialMock(Client::class, ['post']);

        $this->registration = new Registration($this->client);
    }

    /**
     * @test
     * @throws InvalidApiResponseException
     */
    public function shouldFetchNewToken(): void
    {
        $this->client->method('post')
            ->willReturn(
                new Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode(['data' => [
                        'sl_token' => 'test',
                        'email' => 'your@email.address',
                        'client_id' => 'ju16a6m81mhid5ue1z3v2g0uh'
                    ]])
                )
            );

        $token = $this->registration->getToken();

        $this->assertSame('test', $token);
    }


    /**
     * @test
     * @throws InvalidApiResponseException
     */
    public function shouldThrowExceptionsWhenInvalidDataReceived(): void
    {
        $this->client->method('post')
            ->willReturn(new Response(
                200,
                ['content-type' => 'application/json'],
                json_encode([
                    'posts' => [],
                    'page' => 6,
                ])
            ));

        $this->expectException(InvalidApiResponseException::class);

        $this->registration->getToken();
    }
}
