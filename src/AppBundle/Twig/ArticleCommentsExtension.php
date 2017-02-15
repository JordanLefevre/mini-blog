<?php

    namespace AppBundle\Twig;

    class ArticleCommentsExtension extends \Twig_Extension
    {
        protected $articleComments;
        public function getFunctions()
        {
            return array(
                new \Twig_SimpleFunction('nb_comments', array($this, 'nbComments'))
            );
        }

        public function nbComments($id)
        {
            return $this
                    ->articleComments
                    ->getNbComments($id);
        }

        public function getName()
        {
            return 'app_extension';
        }
    }

?>
