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

    private $posts;

    public function __construct(PostsApi $postsApi, TokenManager $tokenManager)
    {
        $this->postsApi = $postsApi;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param int $page
     * @return Post[]
     * @throws InvalidApiResponseException
     */
    public function getPosts(int $page): array
    {
        if (empty($this->posts[$page])) {
            $token = $this->tokenManager->getToken();
            $posts = $this->postsApi->getPosts($token, $page);

            $this->posts[$page] = $posts;
        }

        return $this->posts[$page];
    }
}
