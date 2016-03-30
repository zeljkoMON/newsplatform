<?php
// src/AppBundle/Controller/IndexController

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Utils\TokenAuthenticator;

class IndexController extends Controller
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
        $news = $em->getRepository('AppBundle:News')
            ->findLastEntries(10);

        return $this->render('index/index.html.twig', array(
            'newslist' => $news, 'username' => $username, 'admin' => $admin));

    }
}