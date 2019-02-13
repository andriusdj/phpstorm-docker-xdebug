<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service\PostStats;

use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Service\PostsManager;

/**
 * Class TotalPostsSplitByWeek
 *
 * @package AndriusJankevicius\Supermetrics\Service\PostStats
 */
class TotalPostsSplitByWeek implements PostStatsInterface
{
    /**
     * @var PostsManager
     */
    private $postsManager;

    /**
     * TotalPostsSplitByWeek constructor.
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
     * @throws \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
     */
    public function get(int $pages): array
    {
        $currentPage = 1;

        $totalPostsByWeek = [];

        while ($posts = $this->postsManager->getPosts($currentPage)) {
            $totalPostsByWeek = $this->getTotalPostsByWeek($posts, $totalPostsByWeek);
            $currentPage++;

            if ($currentPage > $pages) {
                break;
            }
        }

        return $totalPostsByWeek;
    }

    /**
     * @param Post[] $posts
     * @param int[]  $totalPostsByWeek
     *
     * @return array
     */
    private function getTotalPostsByWeek(array $posts, array $totalPostsByWeek): array
    {
        foreach ($posts as $post) {
            $week = $post->createdAt->format('\W\e\e\k W, o');
            if (!isset($totalPostsByWeek[$week])) {
                $totalPostsByWeek[$week] = 0;
            }
            $totalPostsByWeek[$week]++;
        }

        return $totalPostsByWeek;
    }

    /**
     * The name of the stats entry
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Total posts split by week';
    }
}
