<?php
// src/AppBundle/Entity/News

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

class News
    /**
     * @ORM\Entity(repositoryClass="AppBundle\Entity\NewsRepository")
     * @ORM\Table(name="news")
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
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $text;
    /**
     * @ORM\Column(type="text")
     */
    protected $author;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="news", orphanRemoval=true, cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="news_id", onDelete="CASCADE")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="news", cascade={"persist"})
     * @ORM\JoinTable(name="tags_and_news")
     */
    protected $tags;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return News
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
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
     * @return News
     */
    public function setAuthor($author)
    {
        $this->author = $author;

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
     * @return News
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     * @return News
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add tags
     *
     * @param \AppBundle\Entity\Tag $tags
     * @return News
     */
    public function addTag(\AppBundle\Entity\Tag $tag)
    {
        if (!($this->tags->contains($tag))) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function removeAllTags()
    {
        $this->tags->clear();
    }

    public function removeAllComments()
    {
        $this->comments->clear();
    }
}
