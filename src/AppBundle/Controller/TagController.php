<?php
// src/AppBundle/Controller/TagController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TagController extends Controller
{
    /**
     * @Route("/tags/{tag}")
     */
    public function tagAction($tag)
    {
        $tagslist = new Tag();
        $em = $this->getDoctrine()->getRepository('AppBundle:Tag');
        $tagslist = $em->findByTag($tag);


        return $this->render('default/tag.html.twig', array(
            'tagslist' => $tagslist->getNews()->toArray()));
    }
}