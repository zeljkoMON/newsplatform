<?php
// src/AppBundle/Controller/EditUsersController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EditUsersController extends Controller
{
    /**
     * @Route("/edit-users")
     */
    public function editAction()
    {
        $userlist = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Users')
            ->getAllUsers();
        return $this->render('default/edit-users.html.twig', array('userlist' => $userlist));
    }
}