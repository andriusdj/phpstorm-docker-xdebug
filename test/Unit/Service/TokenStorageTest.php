<?php
declare(strict_types=1);

namespace Test\Unit\Service;


use AndriusJankevicius\Supermetrics\Entity\Token;
use AndriusJankevicius\Supermetrics\Model\PersistedNameValueStore;
use AndriusJankevicius\Supermetrics\Service\TokenStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TokenStorageTest extends TestCase
{
    /** @var TokenStorage */
    private $storage;

    /** @var MockObject */
    private $model;

    protected function setUp(): void
    {
        $this->model = $this->createMock(PersistedNameValueStore::class);

        $this->storage = new TokenStorage($this->model);
    }

    /**
     * @test
     */
    public function shouldLoadAndFindData(): void
    {
        $this->model->expects($this->once())
            ->method('find')
            ->with('sl_token');

        $this->storage->getByName('sl_token');
    }

    /**
     * @test
     */
    public function shouldSaveToken(): void
    {
        $token = new Token();

        $this->model->expects($this->once())
            ->method('persist')
            ->with('test', $token);
        $this->model->expects($this->once())
            ->method('flush');

        $this->storage->save('test', $token);
    }
}
