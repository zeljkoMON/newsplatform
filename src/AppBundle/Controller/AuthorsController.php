<?php

namespace AppBundle\Controller;

use AppBundle\Utils\Authenticator;
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
        $secret = $this->container->getParameter('secret');
        $cookie = 'user';
        $authenticator = new Authenticator($secret, $cookie);
        $user = $authenticator->getUser();

        $form = $this->createFormBuilder()
            ->add('author', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->get('author')->getData();
            return $this->redirect('/authors/' . $author);
        }
        return $this->render('authors/index.html.twig', array('form' => $form->createView(),
            'username' => $user->getUsername(), 'admin' => $user->getAdmin()));
    }

    /**
     * @Route("/authors/{author}")
     * @param $author
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showByAuthorAction($author)
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'user';
        $authenticator = new Authenticator($secret, $cookie);
        $user = $authenticator->getUser();

        $em = $this->getDoctrine()->getManager();
        $newsList = $em->getRepository('AppBundle:News')
            ->findByAuthor($author);

        return $this->render('index/index.html.twig', array(
            'newsList' => $newsList, 'username' => $user->getUsername(), 'admin' => $user->getAdmin()));
    }
}