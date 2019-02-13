<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 09/02/19
 * Time: 23:57
 */

namespace Test\Unit\Api;


use AndriusJankevicius\Supermetrics\Api\Registration;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * @var Registration
     */
    private $registration;
    /**
     * @var MockObject
     */
    private $client;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);

        $this->registration = new Registration($this->client);
    }

    /**
     * @test
     * @throws \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
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
     * @expectedException \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
     */
    public function shouldThrowExceptionsWhenInvalidDataReceived(): void
    {
        $this->client->method('post')
            ->willReturn(new Response(
                200,
                ['content-type' => 'application/json'],
                stream_for(json_encode([
                    'posts' => [],
                    'page' => 6,
                ]))
            ));

        $this->registration->getToken();
    }
}
