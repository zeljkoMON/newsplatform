<?php
// src/AppBundle/Controller/TagController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TagController extends Controller
{
    /**
     * @Route("/tags/{tag}")
     */
    public function tagAction($tag)
    {
        $em = $this->getDoctrine()->getRepository('AppBundle:Tag');
        $tagslist = $em->findByTag($tag);


        return $this->render('default/tag.html.twig', array(
            'tagslist' => $tagslist->getNews()->toArray()));
    }
}