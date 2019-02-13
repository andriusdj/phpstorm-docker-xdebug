<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service\PostStats;

use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use AndriusJankevicius\Supermetrics\Service\PostsManager;

/**
 * Class LongestPostByCharacterLengthPerMonth
 *
 * @package AndriusJankevicius\Supermetrics\Service\PostStats
 */
class LongestPostByCharacterLengthPerMonth implements PostStatsInterface
{
    /**
     * @var PostsManager
     */
    private $postsManager;

    /**
     * LongestPostByCharacterLengthPerMonth constructor.
     *
     * @param PostsManager $posts
     */
    public function __construct(PostsManager $posts)
    {
        $this->postsManager = $posts;
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
        while ($posts = $this->postsManager->getPosts($currentPage)) {
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
