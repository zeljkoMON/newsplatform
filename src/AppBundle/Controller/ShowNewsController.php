<?php
// src/AppBundle/Controller/ShowNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\Authenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowNewsController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $user = new Users();
        $secret = $this->container->getParameter('secret');
        $cookie = 'user';
        $authenticator = new Authenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $user = $authenticator->getUser();
        }

        $em = $this->getDoctrine()->getManager();
        $newsList = $em->getRepository('AppBundle:News')
            ->findLastEntries(10);

        return $this->render('index/index.html.twig', array(
            'newsList' => $newsList, 'username' => $user->getUsername(), 'admin' => $user->getAdmin()));

    }
}
