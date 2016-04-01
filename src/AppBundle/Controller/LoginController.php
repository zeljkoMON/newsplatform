<?php
// src/AppBundle/Controller/LoginController.php
namespace AppBundle\Controller;

use AppBundle\Entity\BannedIp;
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
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $user = new Users();
        $user->setUsername('zika');
        $user->setPassword('sifra');
        $user->setAdmin(false);
        $ip = $_SERVER['REMOTE_ADDR'];
        $banned = false;
        $secret = $this->container->getParameter('secret');
        $em = $this->getDoctrine()->getManager();
        $bannedIp = $em->getRepository('AppBundle:BannedIp')
            ->findByIp($ip);
        if ($bannedIp <> null) {
            if (($bannedIp->getTime() - time() + 300) > 0) {
                $banned = true;

            } else {
                $em->remove($bannedIp);
                $em->flush();
                $_SESSION['counter'] = 0;

            }
        }


        $form = $this->createForm(UserType::class, $user)
            ->add('login', SubmitType::class, array('label' => 'Login'))
            ->add('check', CheckboxType::class, array('mapped' => false, 'required' => false));

        $form->handleRequest($request);

        if (!$banned) {
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();
            $user = $em->getRepository('AppBundle:Users')
                ->findByName($username);
            if ($form->isValid()) {
                if ($form->get('login')->isClicked()) {
                    if ($user <> null) {
                        $salt = $user->getSalt();
                        $passwordHash = hash('sha256', $password . $salt);
                        if ($user->getPassword() == $passwordHash) {
                            $admin = $user->getAdmin();
                            $username = $user->getUsername();
                            $_SESSION['counter'] = 0;
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
                        }
                        $this->redirect('1270.0.0.1/login');
                    }
                    $this->redirect('1270.0.0.1/login');
                    if (isset($_SESSION['counter'])) {
                        $_SESSION['counter']++;
                    } else $_SESSION['counter'] = 0;
                    if ($_SESSION['counter'] > 3) {
                        $bannedIp = new BannedIp();
                        $bannedIp->setIp($ip);
                        $bannedIp->setTime();
                        $em->merge($bannedIp);
                        $em->flush();
                    }
                }

            }
        } else return new Response(
            '<html><body>' . 'Due excessive login attempts you are banned 5 minutes' . '</body></html>');

        return $this->render('default/login.html.twig', array(
            'form' => $form->createView(), 'counter' => $_SESSION['counter']));
    }

}