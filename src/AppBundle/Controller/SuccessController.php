<?php
// src/AppBundle/SuccessController

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SuccessController extends Controller
{
    public function userPanelAction()
    {
        return $this->render('default/user-panel.html.twig');
    }

    public function notLoggedAction()
    {
        return new Response(
            '<html><body>' . 'You need to login' . '</body></html>');
    }
}