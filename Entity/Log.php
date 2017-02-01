<?php

namespace Lthrt\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lthrt\EntityBundle\Repository\LogRepository")
 */
class Log
{
    use GetSetTrait;
    use IdTrait;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="LogType")
     */
    private $type;

    /**
     * @var integer
     *
     * entityId
     *
     * @ORM\Column(name="eid", type="integer")
     */
    private $eid;

    /**
     * @var string
     *
     * @ORM\Column(name="app_user", type="text", nullable=true)
     */
    private $appUser;

    /**
     * @var string
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255,nullable=true)
     */
    private $class = "{}";

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=255, nullable=true)
     */
    private $method = "{}";
    /**

     * @var string
     *
     * @ORM\Column(name="json", type="text")
     */
    private $json = "{}";
}
