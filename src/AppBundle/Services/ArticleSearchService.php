<?php

    namespace AppBundle\Services;

    class ArticleSearchService {
        private $doctrine;

        public function __construct($doctrine)
        {
            $this->doctrine = $doctrine;
        }

        public function searchArticle($query)
        {
            return $this
            ->doctrine
            ->getRepository('AppBundle:Article')
            ->searchArticleByQuery($query);
        }

        public function getNbComments($id)
        {
            return $this
            ->doctrine
            ->getRepository('AppBundle:Article')
            ->getNbComments($id);
        }
    }
?>
