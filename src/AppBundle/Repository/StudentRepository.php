<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Classs;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Student;

class StudentRepository extends EntityRepository
{
    /**
     * @param Classs $class
     * @param string $sorting
     * @return array
     */
    public function getStudentsByClassSorted($class, $sorting='lastName')
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'i', 'c', 'int', 'com', 'g', 'sa')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->andWhere('s.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false)
            ->leftJoin('s.island', 'i')
            ->leftJoin('s.class', 'c')
            ->leftJoin('s.participations', 'int')
            ->leftJoin('s.comments', 'com')
            ->leftJoin('s.sanctions', 'sa')
            ->leftJoin('s.grades', 'g');

        switch ($sorting) {
            case 'lastName':
            case 'firstName':
            case 'island':
                $qb->orderBy('s.' . $sorting, 'ASC');
                break;
            default:
                $qb->orderBy('s.lastName', 'ASC');
                break;
        }

        $qb->addOrderBy('sa.date', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param Student $student
     */
    public function getCompleteStudent($student)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'i', 'c', 'int', 'com', 'sk', 'sa')
            ->where('s.id = :id')
            ->setParameter('id', $student)
            ->leftJoin('s.island', 'i')
            ->leftJoin('s.class', 'c')
            ->leftJoin('s.participations', 'int')
            ->leftJoin('s.comments', 'com')
            ->leftJoin('s.skills', 'sk')
            ->leftJoin('s.sanctions', 'sa')
            ->orderBy('sa.date', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $input
     * @return array
     */
    public function getStudentsByNameLike($input) {
        $inputParts = explode(' ', $input);

        $qb = $this->createQueryBuilder('s')
            ->where('s.lastName LIKE :input')
            ->orWhere('s.firstName LIKE :input')
            ->setParameter('input', '%' . $input . '%')
            ->andWhere('s.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false);

        foreach($inputParts as $part) {
            $qb->orWhere('s.lastName LIKE :part')
               ->orWhere('s.firstName LIKE :part')
               ->setParameter('part', '%' . $part . '%');
        }

        return $qb->getQuery()->getResult();
    }

}
