<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValueTrait.
 */
trait ValueTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;
}
