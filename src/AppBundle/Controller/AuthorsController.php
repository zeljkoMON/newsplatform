<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AuthorsController extends Controller
{

    /**
     * @Route("/search-author")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('author', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->get('author')->getData();
            return $this->redirect('/authors/' . $author);
        }
        return $this->render('authors/index.html.twig', array('form' => $form->createView()));
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