<?php
// src/AppBundle/Entity/UsersRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

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

    public function findByName($username)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT u FROM AppBundle:Users u
            WHERE u.username = :uname')
            ->setParameter('uname', $username)
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

    public function getAllUsers()
    {
        $users = $this->getEntityManager()
            ->createQuery('SELECT u FROM AppBundle:Users u')
            ->getResult();
        return new ArrayCollection($users);
    }

    public function mailExists($email)
    {
        $result = $this->getEntityManager()
            ->createQuery('SELECT u FROM AppBundle:Users u
            WHERE u.email = :email')
            ->setParameter('email', $email)
            ->getOneOrNullResult();
        if ($result == null) {
            return false;
        } else return true;
    }
}