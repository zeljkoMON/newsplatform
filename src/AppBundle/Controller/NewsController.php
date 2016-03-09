<?php
// src/AppBundle/Controller/NewsController.php
namespace AppBundle\Controller;

use AppBundle\Entity\News;
use Symfony\Component\HttpFoundation\Response;
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

        return new Response(
            '<html><body>' . var_dump($news) . '</body></html>');

    }

    /**
     * @Route("/news")
     */
    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->findLastEntries(10);


        return new Response(
            '<html><body>' . var_dump($news) . '</body></html>');
    }
}