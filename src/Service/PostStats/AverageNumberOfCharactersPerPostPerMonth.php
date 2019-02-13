<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Service\PostStats;

use AndriusJankevicius\Supermetrics\Entity\Post;
use AndriusJankevicius\Supermetrics\Service\PostsManager;

/**
 * Class AverageNumberOfCharactersPerPostPerMonth
 *
 * @package AndriusJankevicius\Supermetrics\Service\PostStats
 */
class AverageNumberOfCharactersPerPostPerMonth implements PostStatsInterface
{
    /**
     * @var PostsManager
     */
    private $postsManager;

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
            $totals = $this->getTotalNumberOfCharactersPerPostPerMonth($posts, $totals);
            $currentPage++;

            if ($currentPage > $pages) {
                break;
            }
        }

        return $this->getAverageNumberOfCharacterPerPostPerMonth($totals);
    }

    /**
     * @param Post[] $posts
     * @param array $totals
     *
     * @return array
     */
    private function getTotalNumberOfCharactersPerPostPerMonth(array $posts, array $totals): array
    {
        foreach ($posts as $post) {
            $numberOfCharacters = \strlen($post->post);
            $month = $post->createdAt->format('F, Y');

            if (!isset($totals[$month])) {
                $totals[$month] = [
                    'total_length' => 0,
                    'count_of_posts' => 0,
                ];
            }

            $totals[$month] = [
                'total_length' => $totals[$month]['total_length'] + $numberOfCharacters,
                'count_of_posts' => $totals[$month]['count_of_posts'] + 1,
            ];
        }

        return $totals;
    }

    /**
     * @param array $totals
     *
     * @return array
     */
    private function getAverageNumberOfCharacterPerPostPerMonth(array $totals): array
    {
        $result = [];
        foreach ($totals as $month => $data) {
            $result[$month] = $data['total_length'] / $data['count_of_posts'];
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
        return 'Average character length / post / month';
    }
}
