<?php
// src/AppBundle/Controller/AccountController

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
            $form = $this->createForm(new UserType(), $user)
                ->add('newpass', PasswordType::class, array('mapped' => false))
                ->add('confirmpass', PasswordType::class, array('mapped' => false))
                ->add('changepass', SubmitType::class, array('label' => 'Change password'));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $username = $form->get('username')->getData();
                $oldPassword = $form->get('password')->getData();
                $newpass = $form->get('newpass')->getData();
                $confirmpass = $form->get('confirmpass')->getData();

                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AppBundle:Users')
                    ->findByName($username);

                if ($user <> null) {
                    $salt = $user->getSalt();
                    $oldPasswordHash = hash('sha256', $oldPassword . $salt);
                    if ($user->getPassword() == $oldPasswordHash) {
                        if ($newpass <> '' && $newpass == $confirmpass) {
                            $salt = bin2hex(openssl_random_pseudo_bytes(32));
                            $user->setSalt($salt);
                            $user->setPassword($newpass);
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