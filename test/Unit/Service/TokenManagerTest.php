<?php
declare(strict_types=1);

namespace Test\Unit\Service;

use AndriusJankevicius\Supermetrics\Api\Registration;
use AndriusJankevicius\Supermetrics\Entity\Token;
use AndriusJankevicius\Supermetrics\Service\TokenManager;
use AndriusJankevicius\Supermetrics\Service\TokenStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TokenManagerTest extends TestCase
{
    /** @var MockObject */
    private $tokenStorage;
    /** @var MockObject */
    private $registration;

    /** @var TokenManager */
    private $tokenManager;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorage::class);
        $this->registration = $this->createMock(Registration::class);

        $this->tokenManager = new TokenManager($this->tokenStorage, $this->registration);
    }

    /**
     * @test
     * @throws \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
     */
    public function shouldFetchAndSaveNewTokenFromAPI(): void
    {
        $this->registration
            ->method('getToken')
            ->willReturn('test');
        $this->tokenStorage
            ->method('getByName')
            ->willReturn(null);

        $this->registration->expects($this->once())
            ->method('getToken');
        $this->tokenStorage->expects($this->once())
            ->method('save');

        $token = $this->tokenManager->getToken();

        $this->assertSame('test', $token);
    }

    /**
     * @test
     * @throws \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
     */
    public function shouldGetTokenFromDB(): void
    {
        $slToken = new Token();
        $slToken->validTill = new \DateTime('+1 hour');
        $slToken->token = 'test2';

        $this->registration
            ->method('getToken')
            ->willReturn('test1');
        $this->tokenStorage
            ->method('getByName')
            ->willReturn($slToken);

        $this->tokenStorage->expects($this->once())
            ->method('getByName');
        $this->registration->expects($this->never())
            ->method('getToken');
        $this->tokenStorage->expects($this->never())
            ->method('save');

        $token = $this->tokenManager->getToken();

        $this->assertSame('test2', $token);
    }
}
