<?php
// src/AppBundle/Controller/IndexController

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->findLastEntries(10);

        return $this->render('index/index.html.twig', array(
            'newslist' => $news));

    }
}