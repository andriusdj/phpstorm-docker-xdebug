<?php
declare(strict_types=1);

namespace AndriusJankevicius\Supermetrics\Entity;

class Post
{
    /** @var string */
    public $id;
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
     *
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->post = $data['post'];
        $this->createdAt = new \DateTime($data['createdAt']);
        $this->user = $data['user'];
        $this->id = $data['id'];
    }

    /**
     * array(
     *  array (
     *      'id' => 'post5c63cd7eb6607_471cd924',
     *      'from_name' => 'Lael Vassel',
     *      'from_id' => 'user_0',
     *      'message' => 'check handy aspect achievement sweet bury snub try railroad falsify gain underline option suntan noble approval shorts clinic feature consumption wine kidney swipe witness talkative program avant-garde pot recording survival coin admit hell miscarriage wreck integration organize freeze sacrifice lie shout wood opposition falsify race opposition cover charter rally point follow cash thoughtful test final trolley psychology trade romantic sunshine facade rush blade seller throne west rough retired information heavy achievement test delete',
     *      'type' => 'status',
     *      'created_time' => '2019-01-25T13:12:46+00:00',
     *  ),
     *  ...
     * )
     *
     * @param array $apiData
     *
     * @return Post
     * @throws \Exception
     */
    public static function createFromApi(array $apiData): self
    {
        $data = [
            'id' => $apiData['id'],
            'post' => $apiData['message'],
            'createdAt' => $apiData['created_time'],
            'user' => $apiData['from_id'],
        ];

        return new self($data);
    }
}
