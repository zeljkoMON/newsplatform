<?php
// src AppBundle/Controller/ResetPasswordController

namespace AppBundle\Controller;

use AppBundle\Utils\JwtToken;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    /**
     * @Route("/reset-password")
     * @Route("/reset-password/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $authenticator = $this->get('app.authenticator');
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $form = $this->createFormBuilder()
                ->add('username', TextType::class)
                ->add('email', EmailType::class)
                ->add('submit', SubmitType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {
                $username = $form->get('username')->getData();
                $email = $form->get('email')->getData();
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AppBundle:Users')
                    ->findByName($username);
                if ($user <> null) {
                    if ($user->getEmail() == $email) {
                        $secret = $this->container->getParameter('secret');
                        $token = new JwtToken($username, $secret, $user->getAdmin(), 600);
                        $subject = 'Password reset request';
                        $msg = 'http:/127.0.0.1/reset-password/' . $token->getString();
                        mail($email, $subject, $msg);
                    }
                } else return new Response('<html><body>' . 'User/email not found' . '</body></html>');
            }
            return $this->render(':default:reset-password.html.twig', array('form' => $form->createView()));
        } else $this->redirect('/not-logged');
        return $this->redirect('/not-logged');
    }

    /**
     * @Route("/reset-password/{token}")
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function resetAction(Request $request, $token)
    {
        $secret = $this->container->getParameter('secret');
        $signer = new Sha256();
        $data = new ValidationData();
        $data->setCurrentTime(time());
        $token = (new Parser())->parse((string)$token);
        if ($token->verify($signer, $secret) && $token->validate($data)) {
            $form = $this->createFormBuilder()
                ->add('password', PasswordType::class)
                ->add('confirmPass', PasswordType::class)
                ->add('submit', SubmitType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                if ($form->get('password')->getData() ==
                    $form->get('confirmPass')->getData()
                ) {

                    $em = $this->getDoctrine()->getManager();
                    $username = $token->getClaim('username');
                    $password = $form->get('password')->getData();
                    $user = $em->getRepository('AppBundle:Users')
                        ->findByName($username);
                    $user->createSalt();
                    $user->setPassword($password);
                    $em->getRepository('AppBundle:Users')
                        ->updateUser($user);
                    $this->redirect('/');

                } else return new Response(
                    '<html><body>' . 'Passwords did not match' . '</body></html>');

            }
            return $this->render(':default:reset-password.html.twig', array('form' => $form->createView()));
        }

        return new Response('<html><body>' . 'Invalid token/request timed out' . '</body></html>');
    }
}
