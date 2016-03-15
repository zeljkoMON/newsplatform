<?php
// src/AppBundle/Entity/UsersRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{
    public function findByUser(Users $user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u FROM AppBundle:Users u
            WHERE u.username = :uname AND u.password = :pass ')
            ->setParameters(array('uname' => $user->getUsername(), 'pass' => $user->getPassword()))
            ->getOneOrNullResult();
    }

    public function updateUser(Users $user)
    {
        $this->getEntityManager()
            ->merge($user);
        $this->getEntityManager()
            ->flush();
        return true;
    }
}