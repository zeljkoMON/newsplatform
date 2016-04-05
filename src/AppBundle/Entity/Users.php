<?php
// src/AppBundle/Entity/Users.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Users
    /**
     * @ORM\Entity(repositoryClass="AppBundle\Entity\UsersRepository")
     * @ORM\Table(name="users")
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
     * @ORM\Column(type="string", length=64)
     */
    protected $salt;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $admin;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $email;

    public function __sleep()
    {
        return array('id', 'username', 'password', 'salt', 'admin', 'email');
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
     * @return Users
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
     * @return Users
     */
    public function setPassword($password)
    {
        $salt = $this->salt;
        $passwordHash = hash('sha256', $password . $salt);
        $this->password = $passwordHash;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return Users
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

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
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

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
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function copyPendingUser(PendingUser $pendingUser)
    {
        $this->setUsername($pendingUser->getUsername());
        $this->setPassword($pendingUser->getPassword());
        $this->setEmail($pendingUser->getEmail());
        $this->setSalt($pendingUser->getSalt());
        $this->setAdmin(0);

        return $this;
    }

    public function createSalt()
    {
        $this->salt = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
