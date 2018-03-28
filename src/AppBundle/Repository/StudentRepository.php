<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class StudentRepository extends EntityRepository
{
    public function getStudentsByClassSorted($class, $sorting)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->leftJoin('s.interventions', 'i')
            ->orderBy('s.name', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }
}
