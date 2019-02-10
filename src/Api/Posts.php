<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 09/02/19
 * Time: 23:08
 */

namespace AndriusJankevicius\Supermetrics\Api;

use AndriusJankevicius\Supermetrics\Exception\InvalidApiResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;

/**
 * Class Posts
 * @package AndriusJankevicius\Supermetrics\Api
 * 2. Use the following endpoint to fetch posts:
 *
 * GET: https://api.supermetrics.com/assignment/posts
 *
 * PARAMS:
 * - sl_token : Token from the register call
 * - page : integer page number of posts (1-10)
 *
 * RETURNS:
 * - page : What page was requested or retrieved
 * - posts : 100 posts per page
 */
class Posts
{
    private $postsUrl = 'https://api.supermetrics.com/assignment/posts';
    /**
     * @var Client
     */
    private $client;

    /**
     * Posts constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token
     * @param int $page
     * @return array
     * @throws InvalidApiResponseException
     */
    public function getPosts(string $token, int $page): array
    {
        $options = [
            RequestOptions::QUERY => [
                'sl_token' => $token,
                'page' => $page,
            ]
        ];

        try {
            $response = $this->client->get($this->postsUrl, $options);
        } catch (GuzzleException $e) {

            throw new InvalidApiResponseException($e->getMessage());
        }
        $content = $response->getBody()->getContents();
        $posts = json_decode($content, true);

        if ($posts['page'] !== $page) {

            throw new InvalidApiResponseException('Wrong posts page returned');
        }

        return $posts['posts'];
    }

}
