<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }


    // Req pour récupérer les projets dans une catégorie
    public function findByCategory(Category $category) {
        return $this->createQueryBuilder('p')
            ->where('p.category = :category')
            ->setParameter('category',$category)
            ->orderBy('p.id','desc')
            ->getQuery()
            ->getResult();
    }

    // Req pour récupérer les projets qui ont au moins le tag que l'on passe en param
    public function findByTag(array $tags) {
        $q = $this->createQueryBuilder("p");
        $q->join('p.tags','t')
            ->where($q->expr()->in('t.id',$tags))
            ->addSelect('t');

        return $q->getQuery()->getResult();
    }



    // /**
    //  * @return Project[] Returns an array of Project objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
