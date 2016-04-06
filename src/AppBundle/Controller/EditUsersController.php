<?php
// src AppBundle/Controller/EditUsersController.php

namespace AppBundle\Controller;

use AppBundle\Utils\Authenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\EditUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EditUsersController extends Controller
{
    /**
     * @Route("/edit-users")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $authenticator = $this->get('app.authenticator');
        $authenticated = $authenticator->isAuthenticated();
        $admin = $authenticator->getUser()->getAdmin();

        if ($authenticated && $admin == 1) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:Users')
                ->findAll();
            $users = array('users' => $users);

            $form = $this->createFormBuilder($users)
                ->add('users', CollectionType::class, array('entry_type' => EditUserType::class))
                ->add('submit', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);
            if ($form->get('submit')->isClicked()) {
                foreach ($form->get('users') as $user) {
                    $em->merge($user->getData());
                }
                $em->flush();
                return $this->redirect('/edit-users');
            }
        } else return new Response(
            '<html><body>' . 'Insufficient privileges' . '</body></html>');

        return $this->render('default/edit-users.html.twig', array(
            'form' => $form->createView()));
    }
}