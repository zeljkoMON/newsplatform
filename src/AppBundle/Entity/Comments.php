<?php
// src/AppBundle/Entity/Comments.php

namespace AppBundle\Entity\src;

use Doctrine\ORM\Mapping as ORM;

class Comments
    /**
     * @ORM\Entity
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
    protected $newsId;

}