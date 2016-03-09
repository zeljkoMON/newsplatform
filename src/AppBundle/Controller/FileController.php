<?php
// src/AppBundle/Controller/FileController.php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller
{
    /**
     * @Route("/file")
     */
    public function writeAction()
    {
        $this->get('app.simplefile')->appendLine('myfile.txt', 'weeeee');
        return new Response(
            '<html><body>' . 'weeeee' . '</body></html>');
    }
}