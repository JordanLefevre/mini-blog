<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('default/index.html.twig', array(
            'lastArticles' => $lastArticles,
        ));
    }
}
