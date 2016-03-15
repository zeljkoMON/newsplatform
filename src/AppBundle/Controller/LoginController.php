<?php
// src/AppBundle/Controller/LoginController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $form = $this->createFormBuilder($userform)
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('login', SubmitType::class, array('label' => 'Login'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emu = $this->getDoctrine()->getManager();
            $user = $emu->getRepository('AppBundle:Users')
                ->findByUser($userform);
            if ($user <> null) {
                setcookie('username', $user->getUsername(), time() + 3600);
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
        if (isset($_COOKIE['username'])) {
            $username = $_COOKIE['username'];
            $formArray = array('username' => $username);
            $form = $this->createFormBuilder($formArray)
                ->add('username', TextType::class)
                ->add('password', PasswordType::class)
                ->add('newpass', PasswordType::class)
                ->add('confirmpass', PasswordType::class)
                ->add('changepass', SubmitType::class, array('label' => 'Change password'))
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $password = $data['password'];
                $newpass = $data['newpass'];
                $confirmpass = $data['confirmpass'];

                $user = new Users();
                $user->setUsername($username);
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
        }
    }
}