<?php
// src/AppBundle/Entity/Tag.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @var string $tag
     *
     * @ORM\Column(type="string", length=50, unique=true )
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

    /**
     * Add news
     *
     * @param News $news
     * @return Tag
     */
    public function addNews(News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param News $news
     */
    public function removeNews(News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }
}
