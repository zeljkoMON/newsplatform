<?php
// src/AppBundle/Controller/AccountController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;
use AppBundle\Utils\TokenAuthenticator;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @Route("/account")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editPasswordAction(Request $request)
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {

            $username = $authenticator->getUser();
            $user = new Users();
            $user->setUsername($username);
            $form = $this->createForm(UserType::class, $user)
                ->add('newPass', PasswordType::class, array('mapped' => false))
                ->add('confirmPass', PasswordType::class, array('mapped' => false))
                ->add('submit', SubmitType::class, array('label' => 'Change password'));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $username = $user->getUsername();
                $oldPassword = $user->getPassword();
                $newPass = $form->get('newPass')->getData();
                $confirmPass = $form->get('confirmPass')->getData();

                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AppBundle:Users')
                    ->findByName($username);

                if ($user <> null) {
                    $salt = $user->getSalt();
                    $oldPasswordHash = hash('sha256', $oldPassword . $salt);
                    if ($user->getPassword() == $oldPasswordHash) {
                        if ($newPass <> '' && $newPass == $confirmPass) {
                            $user->createNewSalt();
                            $user->setPassword($newPass);
                            $em->getRepository('AppBundle:Users')
                                ->updateUser($user);

                            return $this->redirectToRoute('user-panel');
                        } else return new Response('<html><body>' . "Password did't match" . '</body></html>');

                    }
                }
            }
            return $this->render('default/edit-password.html.twig', array(
                'form' => $form->createView()));
        } else return $this->redirectToRoute('notlogged');
    }
}