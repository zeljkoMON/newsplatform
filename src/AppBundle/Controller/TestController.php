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