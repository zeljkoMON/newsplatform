<?php
// src/AppBundle/Controller/NewsController.php
namespace AppBundle\Controller;

use AppBundle\Entity\News;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{
    /**
     * @Route("/authors/{author}")
     */
    public function authorAction($author)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->findByAuthor($author);

        return $this->render('index/index.html.twig', array(
            'newslist' => $news));

    }

    /**
     * @Route("/news")
     */
    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->findLastEntries(5);


        return $this->render('index/index.html.twig', array(
            'newslist' => $news));
    }
}