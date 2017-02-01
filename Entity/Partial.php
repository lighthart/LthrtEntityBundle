<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lthrt\EntityBundle\Repository\PartialRepository")
 */
class Partial implements \Lthrt\EntityBundle\Entity\EntityLedger
{
    use GetSetTrait;
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="json", type="text")
     */
    private $json = "{}";
}
