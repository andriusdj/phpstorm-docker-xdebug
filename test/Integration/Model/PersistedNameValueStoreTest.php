<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 10/02/19
 * Time: 14:51
 */

namespace Test\Integration\Model;


use AndriusJankevicius\Supermetrics\Model\PersistedNameValueStore;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PersistedNameValueStoreTest extends TestCase
{
    private static $testPath = '/tmp/test.json';

    /**
     * @test
     */
    public function shouldCreateStore(): void
    {
        $store = new PersistedNameValueStore(self::$testPath);

        $this->expectException(InvalidArgumentException::class);

        $store->find('test');

        $this->assertFileExists(self::$testPath);
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$testPath);
    }
}
