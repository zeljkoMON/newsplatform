<?php
// src/AppBundle/Controller/ShowNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use AppBundle\Utils\TokenAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShowNewsController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $username = null;
        $admin = 0;
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $username = $authenticator->getUser();
            $admin = $authenticator->isAdmin();
        }

        $em = $this->getDoctrine()->getManager();
        $newsList = $em->getRepository('AppBundle:News')
            ->findLastEntries(10);

        return $this->render('index/index.html.twig', array(
            'newsList' => $newsList, 'username' => $username, 'admin' => $admin));

    }

    /**
     * @Route("/authors/{author}")
     * @param $author
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showByAuthorAction($author)
    {
        $em = $this->getDoctrine()->getManager();
        $newsList = $em->getRepository('AppBundle:News')
            ->findByAuthor($author);

        return $this->render('index/index.html.twig', array(
            'newsList' => $newsList));
    }

    /**
     * @Route("/news/{newsId}")
     * @param Request $request
     * @param $newsId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showNewsAction(Request $request, $newsId)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('AppBundle:News')
            ->find($newsId);

        $comment = new Comment();
        $comment->setAuthor('Author');
        $comment->setText('Text');
        $comment->setDate(new \DateTime('now'));
        $comment->setNews($news);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $news->addComment($comment);
            $this->getDoctrine()->getManager()
                ->persist($news);
            $this->getDoctrine()->getManager()
                ->flush();
        }
        $newsList[] = $news;
        return $this->render('default/comment.html.twig', array(
            'newsList' => $newsList, 'form' => $form->createView()));
    }
}
