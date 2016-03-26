<?php
// src/AppBundle/Controller/LoginController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Utils\JwtToken;
use AppBundle\Form\Type\UserType;
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
        $secret = $this->container->getParameter('secret');

        $form = $this->createForm(new UserType(), $userform)
            ->add('login', SubmitType::class, array('label' => 'Login'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:Users')
                ->findByUser($userform);
            if ($user <> null) {

                $admin = $user->getAdmin();
                $jwt = new JwtToken($user->getUsername(), $secret, $admin);
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

}