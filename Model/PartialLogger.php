<?php

namespace Lthrt\EntityBundle\Model;

use Lthrt\EntityBundle\Entity\Partial;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PartialLogger
{
    private $em;
    private $ignored;
    private $serializer;
    private $user;
    private $class;
    private $method;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function partial($entity)
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = [$normalizer];
        $encoder     = new JsonEncoder();
        $serializer  = new Serializer([$normalizer], [$encoder]);
        $json        = $serializer->serialize($entity, 'json');

        $partial = new Partial();
        $partial->setJson($json);
        $this->em->persist($partial);
        $this->em->flush();
    }
}
