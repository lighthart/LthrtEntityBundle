<?php

namespace Lthrt\EntityBundle\Model;

use Lthrt\EntityBundle\Entity\Ledger;
use Lthrt\EntityBundle\Entity\Log;
use Lthrt\EntityBundle\Entity\LogType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class EntityLogger
{
    private $em;
    private $ignored;
    private $serializer;
    private $user;
    private $class;
    private $method;

    public function __construct(
        $em,
        $user = null,
        $backtrace,
        $ignored = []
    ) {
        $this->ignored = ['id'];
        if ($ignored) {
            $this->ignored = array_merge($this->ignored, $ignored);
        }

        $this->em = $em;

        if ('string' == gettype($user)) {
            $this->user = $user;
        } elseif ('object' == gettype($user)) {
            if (method_exists($user, "getId")) {
                $this->user = $user->getId();
            } elseif (method_exists($user, "__toString")) {
                $this->user = $user->__toString();
            } else {
                $this->user = "unknown";
            }
        } else {
            $this->user = null;
        }

        if ($backtrace && isset($backtrace['class'])) {
            $this->class = $backtrace['class'];
        }

        if ($backtrace && isset($backtrace['function'])) {
            $this->method = $backtrace['function'];
        }
    }

    public function configure($entity)
    {
        $em = $this->em;
        if (is_array($entity)) {
            $metadata = $em->getClassMetadata(get_class(array_slice($entity, 0, 1)[0]));
        } else {
            $metadata = $em->getClassMetadata(get_class($entity));
        }

        $dates = array_filter(
            $metadata->fieldMappings, function ($field) {
                return 'date' == $field['type'] || 'datetime' == $field['type'];
            }
        );

        $callbacks = [];
        array_map(
            function ($field) use ($entity, $em, $dates, &$callbacks) {
                $callback = function ($dateTime) {
                    return $dateTime instanceof \DateTime
                    ? $dateTime->format('Y-m-d H:i:s T')
                    : null;
                };
                $callbacks[$field] = $callback;
            },
            array_keys($dates)
        );

        $assocs = $metadata->getAssociationMappings();
        array_map(
            function ($assoc) use ($entity, $em, $assocs, &$callbacks) {
                if ($assocs[$assoc]['type'] <= 2) {
                    $callback = function ($object) {
                        return $object ? $object->getId() : null;
                    };
                } else {
                    $callback = function ($objects) {
                        return $objects->map(function ($object) {return $object->getId();})->toArray();
                    };
                }
                $callbacks[$assoc] = $callback;
            },
            array_keys($assocs)
        );

        $normalizer = new PropertyNormalizer();
        $normalizer->setIgnoredAttributes($this->ignored);
        $normalizer->setCallbacks($callbacks);
        $normalizers = [$normalizer];
        $encoder     = new JsonEncoder();

        $this->serializer = new Serializer([$normalizer], [$encoder]);
    }

    //
    //  Gets log entity from database
    //
    public function current(
        $entity,
        $asJSON = true
    ) {
        $this->configure($entity);
        if ($asJSON) {
            return $this->serializer->serialize($entity, 'json');
        } else {
            return $entity;
        }
    }

    //
    //  Gets log entity from database
    //
    public function findLog(
        $entity,
        $asJSON = true
    ) {
        $this->configure($entity);
        $logType = $this->em->getRepository('LthrtEntityBundle:LogType')
            ->findOneBy([
                'name' => get_class($entity),
            ]);
        $qb = $this->em->getRepository('LthrtEntityBundle:Log')->createQueryBuilder('log');
        $qb->andWhere($qb->expr()->eq('log.type', ':logType'));
        $qb->andWhere($qb->expr()->eq('log.eid', ':entity'));
        $qb->setParameter(':logType', $logType->getId());
        $qb->setParameter('entity', $entity);
        $qb->addOrderBy('log.updated');
        // $result = $qb->getQuery()->getResult();
        // return $result;
        $result = $qb->getQuery()->getResult() ?: [];
        if ($asJSON) {
            array_map(function ($log) {
                // make sure this is an array, so json_decode true
                $log->json = json_decode($log->json, true);
            }, $result);

            return $this->serializer->serialize($result, 'json');
        } else {
            return $result;
        }
    }

    //
    // Actually writes the log
    //
    // Called in PostPersist/PostUpdate Callback.
    //
    public function log($entity)
    {
        $this->configure($entity);
        $logType = $this->em->getRepository('LthrtEntityBundle:LogType')
            ->findOneBy([
                'name' => get_class($entity),
            ]
            );
        if ($logType) {
        } else {
            $logType       = new LogType();
            $logType->name = get_class($entity);
        }

        $this->em->persist($logType);
        $this->em->flush($logType);

        $log = new Log();

        $log->eid     = $entity->getId();
        $log->type    = $logType;
        $log->appUser = $this->user;
        $log->updated = new \DateTime();
        $log->json    = $this->serializer->serialize($entity, 'json');
        $log->class   = $this->class;
        $log->method  = $this->method;

        $this->em->persist($log);
        $this->em->flush($log);
    }

    //
    // Actually writes the log
    //
    // Called in PostPersist/PostUpdate Callback.
    //
    public function ledger($entity)
    {
        $this->configure($entity);
        $logType = $this->em->getRepository('LthrtEntityBundle:LogType')
            ->findOneBy([
                'name' => get_class($entity),
            ]
            );
        if ($logType) {
        } else {
            $logType       = new LogType();
            $logType->name = get_class($entity);
        }

        $this->em->persist($logType);
        $this->em->flush($logType);
        $ledger = new Ledger();

        $ledger->eid     = $entity->getId();
        $ledger->type    = $logType;
        $ledger->appUser = $this->user;
        $ledger->updated = new \DateTime();
        $ledger->created = new \DateTime();

        $this->em->persist($ledger);
        $this->em->flush($ledger);
    }
}
