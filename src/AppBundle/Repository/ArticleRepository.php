<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLastArticles()
    {
        $qb = $this->createQueryBuilder('n');
        $qb ->where('n.publicationDate <= :now')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('n.publicationDate', 'DESC')
            ->setMaxResults(5);
        return $qb->getQuery()
                   ->getResult();
    }

    public function getAllArticles()
    {
        $qb = $this->createQueryBuilder('n');
        $qb ->where('n.publicationDate <= :now')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('n.publicationDate', 'DESC');
        return $qb->getQuery()
                   ->getResult();
    }

    public function getSomeArticles($begin_results, $nb_per_page)
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->select('n')
            ->where('n.publicationDate <= :now')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('n.publicationDate', 'DESC')
            ->setFirstResult($begin_results)
            ->setMaxResults($nb_per_page);

        $res = new Paginator($qb);
        return $res;
    }

    public function searchArticleByQuery($query)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.name LIKE :query')
            ->andWhere('n.publicationDate <= :now')
            ->setParameter('query', '%'.$query.'%')
            ->setParameter('now', new \DateTime('now'));
        return $qb->getQuery()
                   ->getResult();
    }

    public function getCategoryArticles($category)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.category = :category')
            ->andWhere('n.publicationDate <= :now')
            ->setParameter('category', $category)
            ->setParameter('now', new \DateTime('now'));
        return $qb->getQuery()
                   ->getResult();
    }
}
