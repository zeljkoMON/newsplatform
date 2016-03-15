<?php
// src/AppBundle/Controller/AddNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class AddNewsController extends Controller
{
    /**
     * @Route("/add-news")
     */
    public function addNewsAction(Request $request)
    {
        $news = new News();
        if (isset($_COOKIE['username'])) {
            $news->setAuthor($_COOKIE['username']);
            $username = $_COOKIE['username'];
        } else {
            $news->setAuthor('Zika Zikic');
            $username = 'Guest';
        }

        $news->setTitle('Novi Naslov');
        $news->setText('Dummy text to be displayed');
        $news->setDate(new \DateTime('today'));

        $form = $this->createFormBuilder(new NewsType(), $news)
            ->add('save', SubmitType::class, array('label' => 'Create News'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_COOKIE['username'])) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository('AppBundle:News')
                    ->writeNews($news);
                return $this->redirectToRoute('user-panel');
            } else {
                return $this->redirectToRoute('notlogged');
            }


        }

        return $this->render('default/news.html.twig', array(
            'form' => $form->createView(), 'username' => $username));
    }

    /**
     * @Route("/edit-news")
     */
    public function editNewsAction(Request $request)
    {
        if (isset($_COOKIE['username'])) {
            $username = $_COOKIE['username'];
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
    public function editNews(Request $request, $id)
    {
        if (isset($_COOKIE['username'])) {
            //$id=27;
            $news = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->find($id);

            $form = $this->createFormBuilder($news)
                ->add('title', TextType::class)
                ->add('text', TextType::class)
                ->add('date', DateType::class)
                ->add('edit', SubmitType::class, array('label' => 'edit'))
                ->add('delete', SubmitType::class, array('label' => 'delete'))
                ->getForm();
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get('edit')->isClicked()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->getRepository('AppBundle:News')
                        ->updateNews($news);
                    return $this->redirectToRoute('user-panel');
                }
                if ($form->get('delete')->isClicked()) {
                    if ($news <> null) {
                        $em = $this->getDoctrine()->getManager();
                        $em->getRepository('AppBundle:News')
                            ->removeNews($news);
                    }
                    return $this->redirectToRoute('edit-news');
                }

            }
        } else return $this->redirectToRoute('notlogged');
        return $this->render('default/news-editor.html.twig', array(
            'form' => $form->createView()));
    }
}
