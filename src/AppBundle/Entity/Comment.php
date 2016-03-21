<?php
// src/AppBundle/Entity/Comment.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Comment
    /**
     * @ORM\Entity(repositoryClass="AppBundle\Entity\CommentRepository")
     * @ORM\Table(name="comments")
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
    protected $author;
    /**
     * @ORM\Column(type="text")
     */
    protected $text;
    /**
     * @ORM\Column(type="integer")
     */
    protected $news_id;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;
    /**
     * @ORM\ManyToOne(targetEntity="News", inversedBy="comments")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id")
     */
    protected $news;

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
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return string
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return string
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get news_id
     *
     * @return integer
     */
    public function getNewsId()
    {
        return $this->news_id;
    }

    /**
     * Set news_id
     *
     * @param integer $newsId
     * @return Comment
     */
    public function setNewsId($newsId)
    {
        $this->news_id = $newsId;

        return $this;
    }

    /**
     * Get news
     *
     * @return \AppBundle\Entity\News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set news
     *
     * @param \AppBundle\Entity\News $news
     * @return Comment
     */
    public function setNews(\AppBundle\Entity\News $news = null)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Comment
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}
