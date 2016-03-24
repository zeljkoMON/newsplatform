<?php
// src/AppBundle/Controller/AddNewsController

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\NewsType;
use AppBundle\Utils\AddTags;

class AddNewsController extends Controller
{
    /**
     * @Route("/add-news")
     */
    public function addNewsAction(Request $request)
    {
        $news = new News();
        if (isset($_COOKIE['values'])) {
            $array = unserialize($_COOKIE['values']);
            $username = $array['username'];
            $news->setAuthor($username);
        } else return $this->redirectToRoute('notlogged');

        $news->setTitle('Novi Naslov');
        $news->setText('Dummy text to be displayed');
        $news->setDate(new \DateTime('today'));

        $form = $this->createForm(new NewsType(), $news)
            ->add('save', SubmitType::class, array('label' => 'Create News'))
            ->add('tags', TextType::class, array('label' => 'Tags', 'mapped' => false));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($_COOKIE['values'])) {

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
                //$add = new AddTags($em);
                //$add->add($tags,$news);

                return $this->redirectToRoute('user-panel');
            } else return $this->redirectToRoute('notlogged');
        }

        return $this->render('default/news.html.twig', array(
            'form' => $form->createView(), 'username' => $username));
    }

    /**
     * @Route("/edit-news")
     */
    public function editNewsAction()
    {
        if (isset($_COOKIE['values'])) {
            $array = unserialize($_COOKIE['values']);
            $username = $array['username'];
            $newslist = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->findByAuthor($username);

        } else return $this->redirectToRoute('notlogged');

        return $this->render('default/edit-news.html.twig', array(
            'newslist' => $newslist));
    }

    /**
     * @Route("/edit-news/{id}")
     */
    public function editNews(Request $request, $id)
    {
        if (isset($_COOKIE['value'])) {
            $news = $this->getDoctrine()->getManager()
                ->getRepository('AppBundle:News')
                ->find($id);

            $form = $this->createForm(new NewsType(), $news)
                ->add('edit', SubmitType::class, array('label' => 'edit'))
                ->add('delete', SubmitType::class, array('label' => 'delete'));
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($form->get('edit')->isClicked()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->getRepository('AppBundle:News')
                        ->updateNews($news);
                    return $this->redirectToRoute('user-panel');
                }
                if ($form->get('delete')->isClicked()) {
                    if ($news <> null) {
                        $em = $this->getDoctrine()->getManager();
                        $em->getRepository('AppBundle:News')
                            ->removeNews($news);
                    }
                    return $this->redirectToRoute('edit-news');
                }

            }
        } else return $this->redirectToRoute('notlogged');
        return $this->render('default/news-editor.html.twig', array(
            'form' => $form->createView()));
    }
}
