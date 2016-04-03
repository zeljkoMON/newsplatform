<?php
// src/AppBundle/Controller/AddNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\NewsType;
use AppBundle\Utils\TokenAuthenticator;

class AddNewsController extends Controller
{
    /**
     * @Route("/add-news")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        $news = new News();

        if ($authenticated) {
            $username = $authenticator->getUser();
            $news->setAuthor($username);
        } else return $this->redirect('/not-logged');

        $news->setTitle('New Title');
        $news->setText('Dummy text to be displayed');
        $news->setDate(new \DateTime('today'));

        $form = $this->createForm(new NewsType(), $news)
            ->add('save', SubmitType::class, array('label' => 'Create News'))
            ->add('tags', TextType::class, array('label' => 'Tags', 'mapped' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($authenticated) {

                $news->setDate(new \DateTime('now'));
                $em = $this->getDoctrine()->getManager();
                $data = $form->get('tags')->getData();
                $tags = explode(',', $data);
                foreach ($tags as $value) {
                    $tag = $em->getRepository('AppBundle:Tag')->findByTag($value);
                    if ($tag <> null) {
                        $news->addTag($tag);
                    } else {
                        $tag = new Tag();
                        $tag->setTag($value);
                        $em->persist($tag);
                        $news->addTag($tag);
                    }
                }
                $em->persist($news);
                $em->flush();
                return $this->redirect('/user-panel');

            } else return $this->redirect('/not-logged');
        }

        return $this->render('default/news.html.twig', array(
            'form' => $form->createView(), 'username' => $username));
    }
}