<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\ArticleSearchService;

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
                     ->add('query')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $lastArticles = $this
                                ->container
                                ->get('app.article_search_service')
                                ->searchArticle($form->getData()['query']);
            dump($lastArticles);
        }

        return $this->render('default/index.html.twig', array(
            'lastArticles' => $lastArticles, 'form' => $form->createView()
        ));
    }
}
