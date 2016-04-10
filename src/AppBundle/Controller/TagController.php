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
     * @param $tag
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($tag)
    {
        $authenticator = $this->get('app.authenticator');
        $user = $authenticator->getUser();

        $em = $this->getDoctrine()->getRepository('AppBundle:Tag');
        $tagsList = $em->findByTag($tag);
        if ($tagsList == null) {
            $msg = 'Content not found';
            return $this->render('tags/index.html.twig', array('msg' => $msg));
        }
        return $this->render('tags/index.html.twig', array(
            'newsList' => $tagsList->getNews()->toArray(),
            'username' => $user->getUsername(),
            'admin' => $user->getAdmin()));
    }
}