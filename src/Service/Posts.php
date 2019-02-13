<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;

use AndriusJankevicius\Supermetrics\Api\Posts as PostsApi;
use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use AndriusJankevicius\Supermetrics\Model\PersistedNameValueStore;

/**
 * Class Posts
 * @package AndriusJankevicius\Supermetrics\Service
 */
class Posts
{
    /**
     * @var PostsApi
     */
    private $postsApi;
    /**
     * @var TokenManager
     */
    private $tokenManager;
    /**
     * @var array
     */
    private $posts;
    /**
     * @var PersistedNameValueStore
     */
    private $valueStore;

    /**
     * Posts constructor.
     *
     * @param PostsApi                $postsApi
     * @param TokenManager            $tokenManager
     * @param PersistedNameValueStore $valueStore
     */
    public function __construct(PostsApi $postsApi, TokenManager $tokenManager, PersistedNameValueStore $valueStore)
    {
        $this->postsApi = $postsApi;
        $this->tokenManager = $tokenManager;
        $this->valueStore = $valueStore;
    }

    /**
     * @param int $page
     * @return array
     * @throws InvalidApiResponseException
     */
    public function getPosts(int $page): array
    {
        if (empty($this->posts[$page])) {
            $posts = $this->findPersistedPosts($page);
            if (!$posts) {
                $posts = $this->getPostsFromApi($page);
            }

            if (!$posts) {

                return [];
            }

            $this->save($page, $posts);
            $this->posts[$page] = $this->getPostEntitiesFromApiPosts($posts);
        }

        return $this->posts[$page];
    }

    /**
     * @param int $page
     *
     * @return array
     * @throws InvalidApiResponseException
     */
    private function getPostsFromApi(int $page): array
    {
        $token = $this->tokenManager->getToken();
        try {
            $posts = $this->postsApi->getPosts($token, $page);
        } catch (InvalidApiResponseException $e) {
            if ($e->getMessage() !== 'Wrong posts page returned') {
                throw $e;
            }

            return [];
        }

        return $posts;
    }

    /**
     * @param int $page
     *
     * @return array
     */
    private function findPersistedPosts(int $page): array
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
    private function save(int $page, array $posts): void
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

    /**
     * @param array $posts
     *
     * @return Post[]
     */
    private function getPostEntitiesFromApiPosts(array $posts): array
    {
        $result = [];
        foreach ($posts as $post) {
            try {
                $postEntity = Post::createFromApi($post);
                $result[$postEntity->id] = $postEntity;
            } catch (\Exception $e) {
            }
        }

        return $result;
    }
}
