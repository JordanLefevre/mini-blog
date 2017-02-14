<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\ArticleSearchService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $lastArticles = $em->getRepository('AppBundle:Article')
                       ->getLastArticles();

        $form = $this->createFormBuilder()
                     ->add('query', TextType::class, array(
                            'label'  => 'Rechercher un article'
                     ))
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $lastArticles = $this
                                ->container
                                ->get('app.article_search_service')
                                ->searchArticle($form->getData()['query']);
        }

        return $this->render('default/index.html.twig', array(
            'lastArticles' => $lastArticles, 'form' => $form->createView()
        ));
    }

    /**
     * @Route("/article/page/{id}", name="all_articles")
     */
    public function allArticlesAction(Request $request)
    {
        if($request->get('id') <= 0)
        {
            throw new HttpException(400, "Page non valide.");
        }

        $em = $this->getDoctrine()->getManager();
        $someArticles = $em->getRepository('AppBundle:Article')->getSomeArticles(($request->get('id') - 1) * 10, 10);
        $allArticles = $em->getRepository('AppBundle:Article')->getAllArticles();

        $nbPages = ceil(count($allArticles)/10);

        $form = $this->createFormBuilder()
                     ->add('query', TextType::class, array(
                            'label'  => 'Rechercher un article'
                     ))
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $someArticles = $this
                                ->container
                                ->get('app.article_search_service')
                                ->searchArticle($form->getData()['query']);
        }

        return $this->render('default/allArticles.html.twig', array(
            'someArticles' => $someArticles,
            'form' => $form->createView(),
            'nbPages' => $nbPages
        ));
    }
}
