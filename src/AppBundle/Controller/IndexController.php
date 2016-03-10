<?php
// src/AppBundle/Controller/IndexController

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\News;

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
        for ($i = 0; $i <= (count($news)); $i++) {
            $result[$i] = 0;
        }
        return $this->render('index/index.html.twig', array(
            'newslist' => $news));

    }
}