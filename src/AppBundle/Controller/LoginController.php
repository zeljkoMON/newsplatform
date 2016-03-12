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
                ->findBy(array('username' => $userform->getUsername(), 'password' => $userform->getPassword()));
            if (sizeof($user) <> 0) {
                setcookie('username', $user[0]->getUsername(), time() + 3600);
                return $this->redirectToRoute('add');
            } else {
                return $this->redirectToRoute('notlogged');
            }
        }

        return $this->render('default/login.html.twig', array(
            'form' => $form->createView()));
    }
}