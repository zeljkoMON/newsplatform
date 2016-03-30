<?php
// src/AppBundle/Controller/TestController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\JwtToken;
use AppBundle\Form\Type\UserType;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\EditUserType;
use Lcobucci\JWT\Parser;

class TestController extends Controller
{
    /**
     * @Route("/test")
     */
    public function testAction(Request $request)
    {
        $signer = new Sha256();

        $secret = $this->container->getParameter('secret');
        if (isset($_COOKIE['token'])) {
            $token = (new Parser())->parse((string)$_COOKIE['token']);

            return new Response(
                '<html><body>' . var_dump(strlen($_COOKIE['token'])) . '</body></html>');
        }


        return $this->render('default/test.html.twig', array(
            'form' => $form->createView()));

    }
}