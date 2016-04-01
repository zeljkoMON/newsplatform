<?php
// src/AppBundle/Entity/BannedIp

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class BannedIp
    /**
     * @ORM\Entity(repositoryClass="AppBundle\Entity\BannedIpRepository")
     * @ORM\Table(name="banned_ip")
     */
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="text", length=65)
     */
    protected $ip;
    /**
     * @var
     * @ORM\Column(type="integer")
     */
    protected $time;

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
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return BannedIp
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time
     * @return BannedIp
     */
    public function setTime()
    {
        $this->time = time();

        return $this;
    }
}
