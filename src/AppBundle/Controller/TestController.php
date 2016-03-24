<?php
// src/AppBundle/Controller/TestController.php

namespace AppBundle\Controller;

use Lcobucci\JWT\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Utils\JwtToken;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test")
     */
    public function testAction()
    {
        $secret = $this->container->getParameter('secret');

        return new Response(
            '<html><body>' . $secret . '</body></html>');

    }

}