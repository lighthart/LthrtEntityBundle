<?php

namespace Lthrt\EntityBundle\Model;

use Symfony\Component\Yaml\Parser;

class EntityFetcher
{
    private $em; // entity manager
    private $logger; // log exceptions
    private $aliases; // shortcuts for underscore replaced namespaces

    public function __construct($em, $logger = null, $aliases = null)
    {
        $this->em     = $em;
        $this->logger = $logger;
        $yml          = new Parser();
        foreach ($aliases as $alias => $class) {
            $this->aliases[$alias] = $class;
        }
    }

    public function verifyClass($class)
    {
        if (isset($this->aliases[$class])) {
            $class = $this->aliases[$class];
        } else {
        }

        $metadataFactory = $this->em->getMetadataFactory();

        $error = null;
        try {
            $metadata = $metadataFactory->getMetadataFor($class);
        } catch (\Exception $ex) {
            $error = "Lthrt\\EntityBundle\\Services\\ClassVerifier: '$class' alias not registered in any alias.yml file";
        }
        if ($error) {
            $this->logger->error($error);

            return false;
        } else {
            return $class;
        }
    }

    public function classAssociations($class)
    {
        // verify before using this method
        $metadataFactory = $this->em->getMetadataFactory();
        $metadata        = $metadataFactory->getMetadataFor($class);

        return $metadata->associationMappings;
    }

    public function getEntity($class, $id)
    {
        $class = $this->verifyClass($class);
        if ($class) {
            $qb = $this->em->getRepository($class)->createQueryBuilder('entity');
            $qb->andWhere($qb->expr()->eq('entity.id', ':id'));
            $qb->setParameter('id', $id);
            $entity = $qb->getQuery()->getOneOrNullResult();
            if ($entity) {
                return $entity;
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function getEntities($class, $ids)
    {
        $class = $this->verifyClass($class);
        if ($class) {
            $qb = $this->em->getRepository($class)->createQueryBuilder('entity');
            $qb->andWhere($qb->expr()->in('entity.id', ':ids'));
            $qb->setParameter('ids', $ids);
            $entities = $qb->getQuery()->getResult();
            if ($entities) {
                return $entities;
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function getAll($class)
    {
        $class = $this->verifyClass($class);
        if ($class) {
            $qb       = $this->em->getRepository($class)->createQueryBuilder('entity');
            $entities = $qb->getQuery()->getResult();
            if ($entities) {
                return $entities;
            } else {
                return;
            }
        } else {
            return;
        }
    }
}
