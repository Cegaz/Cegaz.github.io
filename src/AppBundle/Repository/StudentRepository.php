<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Student;

class StudentRepository extends EntityRepository
{
    public function getStudentsByClassSorted($class, $sorting='name')
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'i', 'c', 'int', 'com')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->leftJoin('s.island', 'i')
            ->leftJoin('s.class', 'c')
            ->leftJoin('s.participations', 'int')
            ->leftJoin('s.comments', 'com');

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

    /**
     * @param Student $student
     */
    public function getCompleteStudent($student)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'i', 'c', 'int', 'com', 'sk')
            ->where('s.id = :id')
            ->setParameter('id', $student)
            ->leftJoin('s.island', 'i')
            ->leftJoin('s.class', 'c')
            ->leftJoin('s.participations', 'int')
            ->leftJoin('s.comments', 'com')
            ->leftJoin('s.skills', 'sk');

        return $qb->getQuery()->getResult();
    }

}
