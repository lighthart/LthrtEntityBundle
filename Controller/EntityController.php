<?php

namespace Lthrt\EntityBundle\Controller;

use Lthrt\EntityBundle\Model\EntityLogger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EntityController extends Controller
{
    public function debugAction(Request $request, $class, $id = null)
    {
        if ($id) {
            if (stripos($id, '_')) {
                $ids  = explode('_', $id);
                $json = $this->get('lthrt.Entity.fetcher')->getEntities($class, $ids);
            } else {
                $json = $this->get('lthrt.Entity.fetcher')->getEntity($class, $id);
            }
        } else {
            $json = $this->get('lthrt.Entity.fetcher')->getAll($class);
        }
        $logger = new EntityLogger($this->getDoctrine()->getManager(), $this->getUser());

        return $this->render('LthrtEntityBundle:Entity:debug.html.twig', [
            'json' => $logger->current($json),
        ]);
    }

    public function logAction(Request $request, $class, $id = null)
    {
        if ($id) {
            $json = $this->get('lthrt.entity.fetcher')->getEntity($class, $id);
        } else {
            $json = null;
        }

        $logger = new EntityLogger($this->getDoctrine()->getManager(), $this->getUser());

        return new Response($logger->findLog($json));
    }

    public function historyAction(Request $request, $class, $id = null)
    {
        if ($id) {
            $json = $this->get('lthrt.entity.fetcher')->getEntity($class, $id);
        } else {
            $json = null;
        }

        $logger = new EntityLogger($this->getDoctrine()->getManager(), $this->getUser());

        return $this->render('LthrtEntityBundle:Entity:debug.html.twig', [
            'json' => $logger->findLog($json),
        ]);
    }

    public function jsonAction(Request $request, $class, $id = null)
    {
        if ($id) {
            if (stripos($id, '_')) {
                $ids  = explode('_', $id);
                $json = $this->get('lthrt.entity.fetcher')->getEntities($class, $ids);
            } else {
                $json = $this->get('lthrt.entity.fetcher')->getEntity($class, $id);
            }
        } else {
            $json = $this->get('lthrt.entity.fetcher')->getAll($class);
        }

        $logger = new EntityLogger($this->getDoctrine()->getManager(), $this->getUser());

        return new Response($logger->current($json));
    }

    public function newAction(Request $request, $class, $id = null)
    {
        $slashClass = str_replace('_', '\\', $class);

        $encoders    = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer  = new Serializer($normalizers, $encoders);

        $entity = $serializer->deserialize($request->getContent(), $slashClass, 'json');

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush($entity);

        return $this->redirect($this->generateUrl('entities_json', ['class' => $class]));
    }

    public function modAction(Request $request, $class, $id = null)
    {
        var_dump($request->request);
        var_dump($request->getContent());
        $slashClass = str_replace('_', '\\', $class);

        $encoders    = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer  = new Serializer($normalizers, $encoders);
        $entity      = $this->getDoctrine()->getManager()->merge(
            $serializer->deserialize($request->getContent(), $slashClass, 'json')
        );
        die;
        if (!$entity->getCreated()) {
            $entity->setCreated(new \DateTime());
        }

        $this->getDoctrine()->getManager()->flush($entity);

        return $this->redirect($this->generateUrl('entities_json', ['class' => $class]));
    }
}
