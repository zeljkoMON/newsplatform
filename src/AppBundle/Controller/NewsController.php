<?php
// src/AppBundle/Controller/NewsController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\News;
use AppBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/news/{newsId}")
     */
    public function showNewsAction(Request $request, $newsId)
    {
        $em = $this->getDoctrine()->getManager();
        $newslist = $em->getRepository('AppBundle:News')
            ->findById($newsId);
        $news = $newslist[0];
        $comment = new Comment();
        $comment->setAuthor('Autor');
        $comment->setText('Text');
        $comment->setDate(new \DateTime());
        $comment->setNews($news);

        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setNews($news);
            $news->addComment($comment);
            $this->getDoctrine()->getManager()
                ->persist($news);
            $this->getDoctrine()->getManager()
                ->flush();

        }

        return $this->render('default/comment.html.twig', array(
            'newslist' => $newslist, 'form' => $form->createView()));
    }
}