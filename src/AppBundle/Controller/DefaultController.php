<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\ArticleSearchService;
use AppBundle\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpKernel\Exception\HttpException;
use \DateTime;

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
            throw new HttpException(404, "Cette page n'existe pas.");
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

    /**
     * @Route("/category/{id}", name="category_articles")
     */
    public function categoryArticlesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findById($request->get('id'));
        if(!$category)
        {
            throw new HttpException(404, "Cette page n'existe pas.");
        }
        $categoryArticles = $em->getRepository('AppBundle:Article')->getCategoryArticles($category[0]);

        return $this->render('default/categoryArticles.html.twig', array(
            'category' => $category[0],
            'categoryArticles' => $categoryArticles
        ));
    }

    /**
     * @Route("/article/{id}", name="details_article")
     */
    public function detailsArticlesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('AppBundle:Article')->findById($request->get('id'));

        if(!$article)
        {
            throw new HttpException(404, "Cette page n'existe pas.");
        }

        $form = $this->createFormBuilder()
                     ->add('username', TextType::class, array(
                            'label'  => 'Votre pseudo'
                     ))
                     ->add('content_comment', TextareaType::class, array(
                            'label'  => 'Votre commentaire',
                            'attr'=> array('class'=>'materialize-textarea')
                     ))
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment = new Comment();
            $comment->setAuthor($form->getData()['username']);
            $comment->setContent($form->getData()['content_comment']);
            $comment->setPublicationDate(new DateTime());

            $em->persist($comment);
            $em->flush($comment);

            $article[0]->addComment($comment);

            $em->persist($article[0]);
            $em->flush($article[0]);
        }

        
        return $this->render('default/detailsArticle.html.twig', array(
            'article' => $article[0],
            'comments' => $article[0]->getComments(),
            'form' => $form->createView()
        ));
    }
}
