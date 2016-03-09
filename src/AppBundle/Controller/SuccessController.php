<?php
// src/AppBundle/SuccessController

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SuccessController extends Controller
{
    public function successAction()
    {
        return new Response(
            '<html><body>' . 'SUCCESS!!!' . '</body></html>');
    }
}