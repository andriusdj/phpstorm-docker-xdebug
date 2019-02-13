<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service;

use AndriusJankevicius\Supermetrics\Api\Posts as PostsApi;
use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;

/**
 * Class Posts
 * @package AndriusJankevicius\Supermetrics\Service
 */
class PostsManager
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
     * @var PostsStorage
     */
    private $postsStorage;

    /**
     * @var array
     */
    private $posts;

    /**
     * Posts constructor.
     *
     * @param PostsApi $postsApi
     * @param TokenManager $tokenManager
     * @param PostsStorage $postsStorage
     */
    public function __construct(PostsApi $postsApi, TokenManager $tokenManager, PostsStorage $postsStorage)
    {
        $this->postsApi = $postsApi;
        $this->tokenManager = $tokenManager;
        $this->postsStorage = $postsStorage;
    }

    /**
     * @param int $page
     * @return array
     * @throws InvalidApiResponseException
     */
    public function getPosts(int $page): array
    {
        if (empty($this->posts[$page])) {
            $posts = $this->postsStorage->findPersistedPosts($page);
            if (!$posts) {
                $posts = $this->getPostsFromApi($page);
            }

            if (!$posts) {

                return [];
            }

            $this->postsStorage->save($page, $posts);
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
