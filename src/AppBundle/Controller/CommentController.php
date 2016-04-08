<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{

    /**
     * @Route("/news/{newsId}")
     * @param Request $request
     * @param $newsId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $newsId)
    {
        $authenticator = $this->get('app.authenticator');
        //$authenticated = $authenticator->isAuthenticated();
        $user = $authenticator->getUser();

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

        return $this->render('comment/index.html.twig', array(
            'newsList' => $newsList, 'form' => $form->createView(),
            'username' => $user->getUsername(), 'admin' => $user->getAdmin()));

    }
}
