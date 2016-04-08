<?php
// src/AppBundle/Entity/TagRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function writeTag(Tag $tag)
    {
        $em = $this->getEntityManager();
        $result = $em->createQuery('SELECT t FROM AppBundle:Tag t
            WHERE t.tag = :tag')
            ->setParameter('tag', $tag->getTag())
            ->getResult();
        $result = array_filter($result);
        if (empty($result)) {
            $em->merge($tag);
            $em->flush();
        }
    }

    public function findByTag($tag)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Tag t
            WHERE t.tag = :tag')
            ->setParameter('tag', $tag)
            ->getOneOrNullResult();
    }

    public function removeOrphans()
    {
        $this->getEntityManager()
            ->createQuery('DELETE FROM AppBundle:Tag t
            WHERE t.news IS EMPTY')
            ->execute();

    }
}