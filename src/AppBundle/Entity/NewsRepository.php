<?php
// src/AppBundle/Entity/NewsRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:News n ORDER BY n.id ASC')
            ->getResult();
    }

    public function findByAuthor($autor)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:News n
            WHERE n.author = :author
            ORDER BY n.id ASC')
            ->setParameter('author', $autor)
            ->getResult();
    }

    public function findByDate($startdate, $enddate)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:News n
            WHERE n.date > :startdate AND n.date < :enddate
            ORDER BY n.id ASC')
            ->setParameters(array('startdate' => $startdate, 'enddate' => $enddate))
            ->getResult();
    }

    public function findNumberOfRows()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT (n) FROM AppBundle:News n')
            ->getSingleScalarResult();
    }

    public function findLastEntries($n)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT n FROM AppBundle:News n ORDER by n.date DESC')
            ->setMaxResults($n)
            ->getResult();
    }

    public function writeNews($news)
    {
        $em = $this->getEntityManager();
        $em->persist($news);
        $em->flush();

    }
}