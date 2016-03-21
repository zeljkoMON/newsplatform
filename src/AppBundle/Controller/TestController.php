<?php
// src/AppBundle/Controller/TestController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TestController extends Controller
{
    /**
     * @Route("/test")
     */
    public function testAction()
    {
        //$tag = new Tag();
        $em = $this->getDoctrine()->getManager();
        $newslist = $em->getRepository('AppBundle:News')
            ->findById(2);

        $news = $newslist[0];

        //$tag = $em->getRepository('AppBundle:Tag')->find(2);
        //$news->addTag($tag);
        //$news->removeAllComments();
        //$em->merge($news);
        //$em->flush();
        $tag = new Tag();
        $tag->setTag('weee');

        $news->addTag($tag);
        $em->merge($news);
        $em->flush();

        return new Response(
            '<html><body>' . var_dump($tag) . '</body></html>');
        /*return $this->render('index/index.html.twig', array(
            'newslist' => $news));*/
    }

}