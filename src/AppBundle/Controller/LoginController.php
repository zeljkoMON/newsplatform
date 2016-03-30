<?php
// src/AppBundle/Controller/LoginController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\JwtToken;
use AppBundle\Form\Type\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
        $userform = new Users();
        $userform->setUsername('zika');
        $userform->setPassword('sifra');
        $userform->setAdmin(false);
        $secret = $this->container->getParameter('secret');

        $form = $this->createForm(new UserType(), $userform)
            ->add('login', SubmitType::class, array('label' => 'Login'))
            ->add('check', CheckboxType::class, array('mapped' => false, 'required' => false));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Users')
                ->findByName($username);

            if ($user <> null) {
                $salt = $user->getSalt();
                $passwordHash = hash('sha256', $password . $salt);
                if ($user->getPassword() == $passwordHash) {
                    $admin = $user->getAdmin();
                    $username = $user->getUsername();
                    if ($form->get('check')->getData()) {
                        $jwt = new JwtToken($user->getUsername(), $secret, $admin, 3600);
                        $token = $jwt->getString();
                        setcookie('token', $token, time() + 3600);
                        return $this->redirectToRoute('user-panel');
                    } else {
                        session_start();
                        $_SESSION['admin'] = $admin;
                        $_SESSION['username'] = $username;
                        return $this->redirectToRoute('user-panel');
                    }
                } else return new Response('<html><body>' . 'You need to login' . '</body></html>');

            } else return new Response('<html><body>' . 'You need to login' . '</body></html>');
        }

        return $this->render('default/login.html.twig', array(
            'form' => $form->createView()));
    }

}