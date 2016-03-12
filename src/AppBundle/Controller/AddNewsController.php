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
     * @Route("/add")
     */
    public function addNewsAction(Request $request)
    {
        $news = new News();
        if (isset($_COOKIE['username'])) {
            $news->setAuthor($_COOKIE['username']);
        } else {
            $news->setAuthor('Zika Zikic');
        }

        $news->setTitle('Novi Naslov');
        $news->setText('Dummy text to be displayed');
        $news->setDate(new \DateTime('today'));

        $form = $this->createFormBuilder($news)
            ->add('author', TextType::class)
            ->add('title', TextType::class)
            ->add('text', TextType::class)
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create News'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_COOKIE['username'])) {
                $em = $this->getDoctrine()->getManager();
                $em->getRepository('AppBundle:News')
                    ->writeNews($news);
                return $this->redirectToRoute('success');
            } else {
                return $this->redirectToRoute('notlogged');
            }


        }

        return $this->render('default/news.html.twig', array(
            'form' => $form->createView(), 'username' => $_COOKIE['username']));
    }
}