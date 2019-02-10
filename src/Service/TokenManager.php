<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;

use AndriusJankevicius\Supermetrics\Api\Registration;
use AndriusJankevicius\Supermetrics\Entity\Token;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;

/**
 * Class TokenManager
 * @package AndriusJankevicius\Supermetrics\Service
 */
class TokenManager
{
    /**
     * @var Registration
     */
    private $registration;
    /**
     * @var TokenStorage
     */
    private $storage;

    /**
     * TokenManager constructor.
     * @param TokenStorage $tokenStorage
     * @param Registration $registration
     */
    public function __construct(TokenStorage $tokenStorage, Registration $registration)
    {
        $this->storage = $tokenStorage;
        $this->registration = $registration;
    }

    /**
     * @return string
     * @throws InvalidApiResponseException
     */
    public function getToken(): string
    {
        $token = $this->findPersisted('sl_token');
        if (!$token) {
            $token = $this->fetchNew();
            $this->save($token);
        }

        return $token;
    }

    /**
     * @param string $name
     * @return null|string
     */
    private function findPersisted(string $name): ?string
    {
        $token = $this->storage->getByName($name);

        if ($token instanceof Token && $this->isValid($token)) {

            return $token->token;
        }

        return null;
    }

    /**
     * @param Token $token
     * @return bool
     */
    private function isValid(Token $token): bool
    {
        $now = new \DateTime();

        return $token->validTill->getTimestamp() > $now->getTimestamp();
    }

    /**
     * @return string
     * @throws InvalidApiResponseException
     */
    private function fetchNew(): string
    {
        return $this->registration->getToken();
    }

    /**
     * @param string $slToken
     */
    private function save(string $slToken): void
    {
        $token = new Token();
        $token->token = $slToken;

        $validTill = new \DateTime();
        $validTill->setTimestamp($validTill->getTimestamp() + Token::VALID_FOR_SECONDS);

        $token->validTill = $validTill;

        $this->storage->save('sl_token', $token);
    }
}
