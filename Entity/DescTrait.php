<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescTrait.
 */
trait DescTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="text")
     */
    private $desc;
}
