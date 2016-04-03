<?php
// src/AppBundle/SuccessController

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Utils\TokenAuthenticator;


class SuccessController extends Controller
{
    /**
     * @return Response
     * @Route("/user-panel")
     */
    public function indexAction()
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();
        $admin = $authenticator->isAdmin();
        $username = $authenticator->getUser();
        if ($authenticated) {
            return $this->render('default/user-panel.html.twig', array('username' => $username, 'admin' => $admin));
        }

        return new Response(
            '<html><body>' . 'You need to login' . '</body></html>');

    }

    /**
     * @return Response
     * @Route("/not-logged")
     */
    public function notLoggedAction()
    {
        return new Response(
            '<html><body>' . 'You need to login' . '</body></html>');
    }
}