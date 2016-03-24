<?php
// src/AppBundle/Controller/LoginController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\JwtToken;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

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
            ->add('login', SubmitType::class, array('label' => 'Login'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emu = $this->getDoctrine()->getManager();
            $user = $emu->getRepository('AppBundle:Users')
                ->findByUser($userform);
            if ($user <> null) {

                $jwt = new JwtToken($user->getUsername(), $secret);
                $token = $jwt->getString();
                setcookie('token', $token, time() + 3600);
                return $this->redirectToRoute('user-panel');
            } else {
                return $this->redirectToRoute('notlogged');
            }
        }

        return $this->render('default/login.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/account")
     */
    public function editPasswordAction(Request $request)
    {
        $signer = new Sha256();
        $secret = $this->container->getParameter('secret');

        if (isset($_COOKIE['token'])) {
            $token = (new Parser())->parse((string)$_COOKIE['token']);
            if ($token->verify($signer, $secret)) {

                $username = $token->getHeader('jti');
                $user = new Users();
                $user->setUsername($username);
                $form = $this->createForm(new UserType(), $user)
                    ->add('newpass', PasswordType::class, array('mapped' => false))
                    ->add('confirmpass', PasswordType::class, array('mapped' => false))
                    ->add('changepass', SubmitType::class, array('label' => 'Change password'));
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $password = $user->getPassword();
                    $newpass = $form->get('newpass')->getData();
                    $confirmpass = $form->get('confirmpass')->getData();;

                    $user->setPassword($password);

                    $em = $this->getDoctrine()->getManager();
                    $user = $em->getRepository('AppBundle:Users')
                        ->findByUser($user);

                    if ($user <> null) {
                        if ($newpass <> '' && $newpass == $confirmpass) {
                            $user->setPassword($newpass);
                            $em->getRepository('AppBundle:Users')
                                ->updateUser($user);
                            return $this->redirectToRoute('user-panel');
                        } else return $this->redirectToRoute('notlogged');
                    }

                }
                return $this->render('default/edit-password.html.twig', array(
                    'form' => $form->createView()));

            } else return $this->redirectToRoute('notlogged');

        } else return $this->redirectToRoute('notlogged');
    }
}