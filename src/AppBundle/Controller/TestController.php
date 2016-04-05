<?php
// src/AppBundle/Controller/TestController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
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
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Users')
            ->findByName('brko');
        $value = serialize($user);

        $user2 = unserialize($value);

        return new Response(
            '<html><body>' . var_dump($user2) . '</body></html>');


    }
}