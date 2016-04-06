<?php
// src/AppBundle/Controller/TestController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test")
     */
    public function indexAction()
    {
        $authenticator = $this->get('app.authenticator');


        return new Response(
            '<html><body>' . var_dump($authenticator->getUser()) . '</body></html>');


    }
}