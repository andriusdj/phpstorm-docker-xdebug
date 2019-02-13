<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;

use AndriusJankevicius\Supermetrics\Model\PersistedNameValueStore;

/**
 * Class PostsStorage
 * @package AndriusJankevicius\Supermetrics\Service
 */
class PostsStorage
{
    /**
     * @var PersistedNameValueStore
     */
    private $valueStore;

    /**
     * PostsStorage constructor.
     * @param PersistedNameValueStore $valueStore
     */
    public function __construct(PersistedNameValueStore $valueStore)
    {
        $this->valueStore = $valueStore;
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function findPersistedPosts(int $page): array
    {
        try {
            $posts = $this->valueStore->find($this->getStoreKey($page));
        } catch (\InvalidArgumentException $e) {

            return [];
        }

        return $posts;
    }

    /**
     * @param int   $page
     * @param array $posts
     */
    public function save(int $page, array $posts): void
    {
        $this->valueStore->persist($this->getStoreKey($page), $posts);
        $this->valueStore->flush();
    }

    /**
     * @param int $page
     *
     * @return string
     */
    private function getStoreKey(int $page): string
    {
        return 'posts-' . $page;
    }
}
