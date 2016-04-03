<?php
// src/AppBundle/Controller/EditNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Form\Type\CommentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\NewsType;
use AppBundle\Utils\TokenAuthenticator;

class EditNewsController extends Controller
{
    /**
     * @Route("/edit-news")
     */
    public function indexAction()
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $username = $authenticator->getUser();
            $newsList = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->findByAuthor($username);

        } else return $this->redirect('/not-logged');

        return $this->render('default/edit-news.html.twig', array(
            'newsList' => $newsList));
    }

    /**
     * @Route("/edit-news/{id}")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editByIdAction(Request $request, $id)
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'token';
        $authenticator = new TokenAuthenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();
        $admin = $authenticator->isAdmin();

        if ($authenticated) {
            $news = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->find($id);
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm(NewsType::class, $news)
                ->add('edit', SubmitType::class, array('label' => 'edit'))
                ->add('delete', SubmitType::class, array('label' => 'delete'))
                ->add('comments', CollectionType::class, array('entry_type' => CommentType::class));

            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get('edit')->isClicked()) {
                    $em->getRepository('AppBundle:News')
                        ->updateNews($news);
                    return $this->redirectToRoute('user-panel');
                }
                if ($form->get('delete')->isClicked()) {
                    if ($news <> null) {
                        $em->getRepository('AppBundle:News')
                            ->removeNews($news);
                    }
                    return $this->redirectToRoute('edit-news');
                }
                foreach ($form->get('comments') as $entry) {
                    $toRemove = $entry->get('post')->isClicked();
                    if ($toRemove) {
                        $comment = $entry->getData();
                        $news->removeComment($comment);
                        $em->flush();
                        return $this->redirect('/edit-news/' . $id);
                    }
                }
            }

        } else return $this->redirect('/not-logged');
        return $this->render('default/news-editor.html.twig', array(
            'form' => $form->createView(), 'admin' => $admin));
    }
}