<?php
/**
 * Created by PhpStorm.
 * User: earthian
 * Date: 10/02/19
 * Time: 12:21
 */

namespace AndriusJankevicius\Supermetrics\Entity;


class Token
{
    public const VALID_FOR_SECONDS = 3600;
    /** @var string */
    public $token;
    /** @var \DateTime */
    public $validTill;
}
