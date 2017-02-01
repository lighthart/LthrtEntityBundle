<?php

namespace Lthrt\EntityBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Lthrt\EntityBundle\Entity\EntityLedger;
use Lthrt\EntityBundle\Entity\EntityLog;
use Lthrt\EntityBundle\Model\EntityLogger;
use Lthrt\EntityBundle\Model\FlushFinder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PostPersistEntityLogging
{
    private $tokens;

    /**
     *  Constructor.
     *
     *  @param Doctrine\ORM\EntityManager
     */
    public function __construct(TokenStorage $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * PrePersist Callback.
     *
     * @param LifecycleEventArgs $args Doctrines lifecycle event args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof EntityLog || $args->getEntity() instanceof EntityLedger) {
            $this->user = $this->tokens && $this->tokens->getToken()
            ? $this->tokens->getToken()->getUser()
            : null;

            $finder  = new FlushFinder();
            $flusher = $finder->getFlusher(debug_backtrace());

            $logger = new EntityLogger(
                $args->getEntityManager(),
                $this->user,
                $flusher
            );

            if ($args->getEntity() instanceof EntityLog) {
                $logger->log($args->getEntity());
            } elseif ($args->getEntity() instanceof EntityLedger) {
                $logger->ledger($args->getEntity());
            }
        }
    }
}
