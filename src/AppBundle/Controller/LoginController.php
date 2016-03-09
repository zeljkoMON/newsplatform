<?php
// src/AppBundle/Controller/LoginController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{
    /**
     * @Route("/login")
     */
    public function loginAction()
    {
        return new Response(
            '<html><body>WEEEEEE</body></html>');
    }
}