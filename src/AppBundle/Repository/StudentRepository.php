<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StudentRepository extends EntityRepository
{
    public function getStudentsByClassSorted($class, $sorting)
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.class = :class')
            ->setParameter('class', $class);

        switch ($sorting) {
            case 'name':
            case 'surname':
            case 'island':
                $qb->orderBy('s.' . $sorting, 'ASC');
                break;
            default:
                $qb->orderBy('s.name', 'ASC');
                break;
        }

        return $qb->getQuery()->getArrayResult();
    }
}
