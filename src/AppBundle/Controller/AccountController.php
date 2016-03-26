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
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class AccountController extends Controller
{
    /**
     * @Route("/account")
     */
    public function editPasswordAction(Request $request)
    {
        $signer = new Sha256();
        $secret = $this->container->getParameter('secret');
        $authenticated = false;
        if (isset($_COOKIE['token'])) {
            $token = (new Parser())->parse((string)$_COOKIE['token']);
            if ($token->verify($signer, $secret)) {
                $authenticated = true;
            }
        }
        if ($authenticated) {

            $username = $token->getClaim('user');
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
    }
}