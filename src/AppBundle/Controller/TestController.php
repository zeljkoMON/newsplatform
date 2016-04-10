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
        $value = 'megerec presec';
        $value = preg_replace('/[^A-Za-z0-9\-, ]/', '', $value);


        return new Response(
            '<html><body>' . var_dump($value) . '</body></html>');


    }
}