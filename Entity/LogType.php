<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogType.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lthrt\EntityBundle\Repository\LogTypeRepository")
 */
class LogType
{
    use GetSetTrait;
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
}
