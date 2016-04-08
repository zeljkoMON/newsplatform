<?php
// src/AppBundle/SuccessController

namespace AppBundle\Controller;

use AppBundle\Utils\Authenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SuccessController extends Controller
{
    /**
     * @return Response
     * @Route("/user-panel")
     */
    public function indexAction()
    {
        $authenticator = $this->get('app.authenticator');
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $user = $authenticator->getUser();
            return $this->render('default/user-panel.html.twig', array('username' => $user->getUsername(),
                'admin' => $user->getAdmin()));
        }

        return $this->render('not-logged/index.html.twig', array(
            'msg' => 'You need to log in order to access this page'));

    }
    /**
     * @return Response
     * @Route("/not-logged")
     */
    public function notLoggedAction()
    {
        return $this->render('not-logged/index.html.twig', array(
            'msg' => 'You need to log in order to access this page'));
    }
}