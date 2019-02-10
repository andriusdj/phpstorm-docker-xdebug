<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 10/02/19
 * Time: 15:10
 */

namespace AndriusJankevicius\Supermetrics\Service;


use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;

class AveragePostsStats
{
    /**
     * @var Posts
     */
    private $posts;

    public function __construct(Posts $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return int
     */
    public function getAverageCharacterLength(): int
    {
        $posts = [];
        $page = 1;

        try {
            while ($batch = $this->posts->getPosts($page)) {
                $posts = array_merge($posts, $batch);
                $page++;
            }
        } catch (InvalidApiResponseException $e) {
        }

        return 1;

    }
}
