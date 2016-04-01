<?php
// src/AppBundle/Controller/CreateAccountController.php

namespace AppBundle\Controller;

use AppBundle\Entity\PendingUser;
use AppBundle\Entity\Users;
use AppBundle\Utils\JwtToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;


class CreateAccountController extends Controller
{
    /**
     * @Route("/create-account")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $user = new PendingUser();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('confirmEmail', EmailType::class, array('mapped' => false))
            ->add('password', PasswordType::class)
            ->add('confirmPass', PasswordType::class, array('mapped' => false))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('email')->getData() == $form->get('confirmEmail')->getData()) {
                if ($form->get('password')->getData() == $form->get('confirmPass')->getData()) {
                    $em = $this->getDoctrine()->getManager();

                    $username = $user->getUsername();
                    $password = $user->getPassword();

                    $secret = $this->container->getParameter('secret');
                    $token = new JwtToken($username, $secret, 0, 600);
                    $user->createNewSalt();
                    $user->setPassword($password);
                    $user->setToken($token->getString());

                    if (!($em->getRepository('AppBundle:Users')
                        ->mailExists($user->getEmail()))
                    ) {
                        try {
                            $em->persist($user);
                            $em->flush();

                            $msg = 'Please activate using this link in next 10 minutes http:/127.0.0.1/activation/' .
                                $user->getToken();
                            mail($user->getEmail(), 'Account activation', $msg);
                        } catch (UniqueConstraintViolationException $e) {
                            return new Response(
                                '<html><body>' . 'Username already in use' . '</body></html>');
                        }

                    } else return new Response(
                        '<html><body>' . 'Email already in use' . '</body></html>');

                }
            }
        }
        return $this->render('default/create-account.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/activation/{token}")
     * @param $token
     * @return Response
     */
    public function activateAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $pendingUser = $em->getRepository('AppBundle:PendingUser')
            ->findByToken($token);
        $token = (new Parser())->parse((string)$token);
        $signer = new Sha256();
        $secret = $this->container->getParameter('secret');
        if ($token->verify($signer, $secret)) {
            $data = new ValidationData();
            $data->setIssuer('http://example.com');
            $data->setAudience('http://example.org');
            $data->setCurrentTime(time());
            if ($token->validate($data)) {
                $user = new Users();
                $user->copyPendingUser($pendingUser);
                $em->remove($pendingUser);
                $em->persist($user);

            } else $em->remove($pendingUser);

        }
        $em->flush();
        return new Response('<html><body>' . "Success!!!!!" . '</body></html>');
    }
}