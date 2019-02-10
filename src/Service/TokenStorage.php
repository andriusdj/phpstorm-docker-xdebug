<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;


use AndriusJankevicius\Supermetrics\Entity\Token;
use AndriusJankevicius\Supermetrics\Model\PersistedNameValueStore;

/**
 * Class TokenStorage
 * @package AndriusJankevicius\Supermetrics\Service
 */
class TokenStorage
{
    /**
     * @var PersistedNameValueStore
     */
    private $valueStore;

    /**
     * TokenStorage constructor.
     * @param PersistedNameValueStore $valueStore
     */
    public function __construct(PersistedNameValueStore $valueStore)
    {
        $this->valueStore = $valueStore;
    }

    /**
     * @param string $name
     * @return null|Token
     */
    public function getByName(string $name): ?Token
    {
        try {
            return $this->valueStore->find($name);
        } catch (\InvalidArgumentException $e) {

            return null;
        }
    }

    /**
     * @param string $name
     * @param Token $token
     */
    public function save(string $name, Token $token): void
    {
        $this->valueStore->persist($name, $token);
        $this->valueStore->flush();
    }
}
