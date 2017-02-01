<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NameTrait.
 */
trait NameTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
