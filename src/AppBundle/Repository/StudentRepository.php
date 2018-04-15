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
            ->leftJoin('s.interventions', 'i');

        switch ($sorting) {
            case 'name':
            case 'surname':
            case 'island':
                $qb->orderBy('s.' . $sorting, 'ASC');
                break;

            case 'last_call':
                $qb->orderBy('last_call', 'ASC');
            case 'call_nb':
                $qb->orderBy('call_nb', 'DESC');
                break;
            default:
                $qb->orderBy('s.name', 'ASC');
                break;
        }

        return $qb->getQuery()->getResult();
    }
}
