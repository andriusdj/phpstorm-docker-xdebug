<?php
/**
 * Created by PhpStorm.
 * User: andrius
 * Date: 19.2.13
 * Time: 14.20
 */

namespace AndriusJankevicius\Supermetrics\Service\PostStats;


use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Service\PostsManager;

class AverageNumberOfPostsPerUserPerMonth implements PostStatsInterface
{
    /**
     * @var PostsManager
     */
    private $postsManager;

    /**
     * AverageNumberOfPostsPerUserPerMonth constructor.
     *
     * @param PostsManager $posts
     */
    public function __construct(PostsManager $posts)
    {
        $this->postsManager = $posts;
    }

    /**
     * Return the stats information
     *
     * @param int $pages
     *
     * @return array
     * @throws \AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException
     */
    public function get(int $pages): array
    {
        $totals = [];

        $currentPage = 1;
        while ($posts = $this->postsManager->getPosts($currentPage)) {
            $totals = $this->getTotalNumberOfPostsPerUserPerMonth($posts, $totals);
            $currentPage++;

            if ($currentPage > $pages) {
                break;
            }
        }

        return $this->getAverageNumberOfPostsPerUserPerMonth($totals);
    }

    /**
     * @param Post[] $posts
     * @param array  $totalNumberOfPostsPerUserPerMonth
     *
     * @return array
     */
    private function getTotalNumberOfPostsPerUserPerMonth(array $posts, array $totalNumberOfPostsPerUserPerMonth): array
    {
        foreach ($posts as $post) {
            $user = $post->user;
            $month = $post->createdAt->format('F, Y');

            if (!isset($totalNumberOfPostsPerUserPerMonth[$user][$month])) {
                $totalNumberOfPostsPerUserPerMonth[$user][$month] = 0;
            }

            $totalNumberOfPostsPerUserPerMonth[$user][$month]++;
        }

        return $totalNumberOfPostsPerUserPerMonth;
    }

    /**
     * @param array $totalNumberOfPostsPeruserPerMonth
     *
     * @return array
     */
    private function getAverageNumberOfPostsPerUserPerMonth(array $totalNumberOfPostsPeruserPerMonth): array
    {
        $result = [];
        foreach ($totalNumberOfPostsPeruserPerMonth as $user => $months) {
            $result[$user] = \array_sum($months) / \count($months);
        }

        return $result;
    }

    /**
     * The name of the stats entry
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Average number of posts per user per month';
    }
}
