<?php
// src AppBundle/UserManager.php

namespace AppBundle\Utils;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserManager implements PasswordEncoderInterface
{
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }

    public function encodePassword($raw, $salt)
    {
        return hash('sha256', $raw);
    }


}