<?php
// src/AppBundle/Entity/PendingUserRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PendingUserRepository extends EntityRepository
{
    public function findByToken($token)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AppBundle:PendingUser p
            WHERE p.token = :token')
            ->setParameter('token', $token)
            ->getSingleResult();
    }

}
