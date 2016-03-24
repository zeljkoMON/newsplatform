<?php
// src/AppBundle/Utils/AddTags.php

namespace AppBundle\Utils;

use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;

class AddTags
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function add($tags, News $news)
    {
        $em = $this->em;
        foreach ($tags as $value) {
            $tag = $em->getRepository('AppBundle:Tag')->findByTag($value);
            // Checks for tags in db, if it exists adds it to news
            if ($tag <> null) {
                $news->addTag($tag);
                $em->persist($news);
                $em->flush();
            } else {
                //if it does not exist, adds it to db and news
                $tag = new Tag();
                $tag->setTag($value);

                $news->addTag($tag);
                $em->persist($news);
                $em->flush();
            }
        }
    }
}