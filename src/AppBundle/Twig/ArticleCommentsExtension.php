<?php

    namespace AppBundle\Twig;

    class ArticleCommentsExtension extends \Twig_Extension
    {        
        public function getFunctions()
        {
            return array(
                new \Twig_SimpleFunction('nb_comments', array($this, 'nbComments')),
            );
        }

        public function nbComments($article)
        {
            return count($article->getComments());
        }

        public function getName()
        {
            return 'app_extension';
        }
    }

?>
