<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service\PostStats;

use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use AndriusJankevicius\Supermetrics\Service\Posts;

/**
 * Class LongestPostByCharacterLengthPerMonth
 *
 * @package AndriusJankevicius\Supermetrics\Service\PostStats
 */
class LongestPostByCharacterLengthPerMonth implements PostStatsInterface
{
    /**
     * @var Posts
     */
    private $posts;

    /**
     * LongestPostByCharacterLengthPerMonth constructor.
     *
     * @param Posts $posts
     */
    public function __construct(Posts $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @param int $pages
     *
     * @return array
     * @throws InvalidApiResponseException
     */
    public function get(int $pages): array
    {
        $longestPostsPerMonth = [];

        $currentPage = 1;
        while ($posts = $this->posts->getPosts($currentPage)) {
            $longestPostsPerMonth = $this->getLongestPostPerMonth($posts, $longestPostsPerMonth);
            $currentPage++;

            if ($currentPage > $pages) {
                break;
            }
        }

        return $longestPostsPerMonth;
    }

    /**
     * @param Post[] $posts
     * @param Post[] $longestPostPerMonth
     *
     * @return array
     */
    private function getLongestPostPerMonth(array $posts, array $longestPostPerMonth): array
    {
        foreach ($posts as $post) {
            $month = $post->createdAt->format('F, Y');
            $postLength = \strlen($post->post);

            if (!isset($longestPostPerMonth[$month]) ||
                (isset($longestPostPerMonth[$month]) && $postLength > \strlen($longestPostPerMonth[$month]->post))
            ) {
                $longestPostPerMonth[$month] = $post;
            }
        }

        return $longestPostPerMonth;
    }

    /**
     * The name of the stats entry
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Longest post by character length / month';
    }
}
