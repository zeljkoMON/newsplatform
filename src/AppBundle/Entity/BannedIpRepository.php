<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BannedIpRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BannedIpRepository extends EntityRepository
{
    public function findByIp($ip)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b FROM AppBundle:BannedIp b
            WHERE b.ip = :ip')
            ->setParameter('ip', $ip)
            ->getOneOrNullResult();
    }
}
