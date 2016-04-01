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
    public function testAction()
    {


        return new Response(
            '<html><body>' . var_dump($_SERVER['REMOTE_ADDR']) . '</body></html>');


    }
}