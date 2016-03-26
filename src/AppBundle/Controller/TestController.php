<?php
// src/AppBundle/Controller/TestController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Entity\News;
use AppBundle\Form\Type\NewsType;
use AppBundle\Form\Type\CommentType;

class TestController extends Controller
{
    /**
     * @Route("/test")
     */
    public function testAction(Request $request)
    {
        $news = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:News')
            ->find(42);

        $form = $this->createForm(NewsType::class, $news)
            ->add('edit', ButtonType::class, array('label' => 'edit'))
            ->add('delete', ButtonType::class, array('label' => 'delete'))
            ->add('comments', CollectionType::class, array('entry_type' => CommentType::class));

        $form->handleRequest($request);
        foreach ($form->get('comments') as $entry) {
            $toRemove = $entry->get('remove')->isClicked();
            if ($toRemove) {
                return new Response(
                    '<html><body>' . 'You need to login' . '</body></html>');
            }
        }
        return $this->render('default/news-editor.html.twig', array(
            'form' => $form->createView()));
    }

}