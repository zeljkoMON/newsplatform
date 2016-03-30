<?php
// src/AppBundle/Entity/PendingUser.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class PendingUser
    /**
     * @ORM\Entity(repositoryClass="AppBundle\Entity\PendingUserRepository")
     * @ORM\Table(name="pending_users")
     */
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=12, unique=true)
     */
    protected $username;
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $password;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $email;
    /**
     * @ORM\Column(type="string", length=225)
     */
    protected $token;
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $salt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return PendingUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return PendingUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return PendingUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return PendingUser
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return PendingUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
}
