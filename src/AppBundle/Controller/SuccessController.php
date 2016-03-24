<?php
// src/AppBundle/SuccessController

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;


class SuccessController extends Controller
{
    public function userPanelAction()
    {
        $signer = new Sha256();
        $secret = $this->container->getParameter('secret');

        if (isset($_COOKIE['token'])) {
            $token = (new Parser())->parse((string)$_COOKIE['token']);
            if ($token->verify($signer, $secret)) {
                $admin = 1;
                return $this->render('default/user-panel.html.twig', array('admin' => $admin));
            }
        }

        return new Response(
            '<html><body>' . 'You need to login' . '</body></html>');

    }

    public function notLoggedAction()
    {
        return new Response(
            '<html><body>' . 'You need to login' . '</body></html>');
    }
}