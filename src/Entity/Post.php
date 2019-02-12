<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Entity;

class Post
{
    /** @var string */
    public $post;
    /** @var \DateTimeInterface */
    public $createdAt;
    /** @var string */
    public $user;

    /**
     * Post constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->post = $data['post'];
        $this->createdAt = new \DateTime($data['createdAt']);
        $this->user = $data['user'];
    }

    /**
     * @param array $apiData
     *
     * @return Post
     */
    public static function createFromApi(array $apiData): self
    {
        $data = [
            'post' => $apiData['post'],
            'createdAt' => $apiData['createdAt'],
            'user' => $apiData['user'],
        ];

        return new self($data);
    }
}
