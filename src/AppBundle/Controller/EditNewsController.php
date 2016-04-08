<?php
// src/AppBundle/Controller/EditNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use AppBundle\Form\Type\CommentType;
use AppBundle\Utils\Authenticator;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\NewsType;

class EditNewsController extends Controller
{
    /**
     * @Route("/edit-news")
     */
    public function indexAction()
    {
        $secret = $this->container->getParameter('secret');
        $cookie = 'user';
        $authenticator = new Authenticator($secret, $cookie);
        $authenticated = $authenticator->isAuthenticated();

        if ($authenticated) {
            $user = $authenticator->getUser();
            $newsList = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->findByAuthor($user->getUsername());

        } else return $this->redirect('/not-logged');

        return $this->render('edit-news/index.html.twig', array(
            'newsList' => $newsList,
            'username' => $user->getUsername()));
    }

    /**
     * @Route("/edit-news/{id}")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editByIdAction(Request $request, $id)
    {
        $authenticator = $this->get('app.authenticator');
        $authenticated = $authenticator->isAuthenticated();
        $user = $authenticator->getUser();
        $admin = $user->getAdmin();

        if ($authenticated) {
            $news = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->find($id);
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm(NewsType::class, $news)
                ->add('tags', TextType::class, array('mapped' => false))
                ->add('edit', SubmitType::class, array('label' => 'edit'))
                ->add('delete', SubmitType::class, array('label' => 'delete'))
                ->add('comments', CollectionType::class, array('entry_type' => CommentType::class));
            $form->get('tags')->setData($news->tagsToString());

            $form->handleRequest($request);

            if ($form->isValid()) {

                if ($form->get('edit')->isClicked()) {
                    $news->removeAllTags();
                    $tagsStr = $form->get('tags')->getData();
                    $tags = explode(',', $tagsStr);
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
                        $em->getRepository('AppBundle:News')
                            ->updateNews($news);
                    }
                    return $this->redirect('/user-panel');
                }
                if ($form->get('delete')->isClicked()) {
                    if ($news <> null) {
                        $em->getRepository('AppBundle:News')
                            ->removeNews($news);
                    }
                    return $this->redirect('/edit-news');
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
            'form' => $form->createView(), 'admin' => $admin,
            'username' => $user->getUsername()));
    }
}
