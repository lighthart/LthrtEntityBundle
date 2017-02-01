<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbbrTrait.
 */
trait AbbrTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="abbr", type="string", length=255)
     */
    private $abbr;
}
