<?php
// src/AppBundle/Entity/Tag.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

class Tag
    /**
     * @ORM\Entity(repositoryClass="AppBundle\Entity\TagRepository")
     * @ORM\Table(name="tags")
     */

{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="text")
     */
    protected $tag;
    /**
     * @ORM\ManyToMany(targetEntity="News", mappedBy="tags")
     * @ORM\JoinTable(name="tags_and_news")
     */
    protected $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
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
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return Tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

}
