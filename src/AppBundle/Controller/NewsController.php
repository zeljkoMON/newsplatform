<?php
// src/AppBundle/Controller/NewsController

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\NewsType;
use AppBundle\Utils\TokenAuthenticator;

class NewsController extends Controller
{
    /**
     * @Route("/add-news")
     */
    public function addNewsAction(Request $request)
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        $news = new News();

        if ($authenticated) {
            $username = $authenticator->getUser();
            $news->setAuthor($username);
        } else return $this->redirectToRoute('notlogged');

        $news->setTitle('Novi Naslov');
        $news->setText('Dummy text to be displayed');
        $news->setDate(new \DateTime('today'));

        $form = $this->createForm(new NewsType(), $news)
            ->add('save', SubmitType::class, array('label' => 'Create News'))
            ->add('tags', TextType::class, array('label' => 'Tags', 'mapped' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($authenticated) {

                $news->setDate(new \DateTime('now'));
                $em = $this->getDoctrine()->getManager();
                $data = $form->get('tags')->getData();
                $tags = explode(',', $data);
                foreach ($tags as $value) {
                    $tag = $em->getRepository('AppBundle:Tag')->findByTag($value);
                    if ($tag <> null) {
                        $news->addTag($tag);
                    } else {
                        $tag = new Tag();
                        $tag->setTag($value);
                        $em->persist($tag);
                        $news->addTag($tag);
                    }
                }
                $em->persist($news);
                $em->flush();
                return $this->redirectToRoute('user-panel');

            } else return $this->redirectToRoute('notlogged');
        }

        return $this->render('default/news.html.twig', array(
            'form' => $form->createView(), 'username' => $username));
    }
    /**
     * @Route("/edit-news")
     */
    public function editNewsAction()
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $username = $authenticator->getUser();
            $newslist = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->findByAuthor($username);

        } else return $this->redirectToRoute('notlogged');

        return $this->render('default/edit-news.html.twig', array(
            'newslist' => $newslist));
    }
    /**
     * @Route("/edit-news/{id}")
     */
    public function editByIdAction(Request $request, $id)
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();
        $admin = $authenticator->isAdmin();

        if ($authenticated) {
            $news = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->find($id);
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm(NewsType::class, $news)
                ->add('edit', SubmitType::class, array('label' => 'edit'))
                ->add('delete', SubmitType::class, array('label' => 'delete'))
                ->add('comments', CollectionType::class, array('entry_type' => CommentType::class));

            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get('edit')->isClicked()) {
                    $em->getRepository('AppBundle:News')
                        ->updateNews($news);
                    return $this->redirectToRoute('user-panel');
                }
                if ($form->get('delete')->isClicked()) {
                    if ($news <> null) {
                        $em->getRepository('AppBundle:News')
                            ->removeNews($news);
                    }
                    return $this->redirectToRoute('edit-news');
                }
                foreach ($form->get('comments') as $entry) {
                    $toRemove = $entry->get('post')->isClicked();
                    if ($toRemove) {
                        $comment = $entry->getData();
                        $news->removeComment($comment);
                        $em->flush();
                        return $this->redirect('http://127.0.0.1/edit-news/' . $id);
                    }
                }
            }

        } else return $this->redirectToRoute('notlogged');
        return $this->render('default/news-editor.html.twig', array(
            'form' => $form->createView(),
            'admin' => $admin));
    }

    /**
     * @Route("/authors/{author}")
     */
    public function showByAuthorAction($author)
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
        $news = $em->getRepository('AppBundle:News')
            ->find($newsId);

        $comment = new Comment();
        $comment->setAuthor('Autor');
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
        $newslist[] = $news;
        return $this->render('default/comment.html.twig', array(
            'newslist' => $newslist, 'form' => $form->createView()));
    }
}
