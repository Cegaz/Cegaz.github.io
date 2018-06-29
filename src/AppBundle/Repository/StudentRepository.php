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
    public function getStudentsByClassSorted($class, $sorting='name')
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'i', 'c', 'int', 'com', 'g')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->leftJoin('s.island', 'i')
            ->leftJoin('s.class', 'c')
            ->leftJoin('s.participations', 'int')
            ->leftJoin('s.comments', 'com')
            ->leftJoin('s.grades', 'g');

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

    /**
     * @param string $input
     * @return array
     */
    public function getStudentsByNameLike($input) {
        $inputParts = explode(' ', $input);

        $qb = $this->createQueryBuilder('s')
            ->where('s.name LIKE :input')
            ->orWhere('s.surname LIKE :input')
            ->setParameter('input', '%' . $input . '%');

        foreach($inputParts as $part) {
            $qb->orWhere('s.name LIKE :part')
               ->orWhere('s.surname LIKE :part')
               ->setParameter('part', '%' . $part . '%');
        }

        return $qb->getQuery()->getResult();
    }

}
