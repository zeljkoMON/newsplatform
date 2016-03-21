<?php
// src/AppBundle/Entity/Comment.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getComments($newsId)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM AppBundle:Comments c WHERE news_id = :id ORDER BY n.id ASC')
            ->setParameter('id', $newsId)
            ->getResult();
    }
}