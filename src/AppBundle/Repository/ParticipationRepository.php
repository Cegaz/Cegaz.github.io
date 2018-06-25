<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class ParticipationRepository extends EntityRepository
{
    /**
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getLastParticipation()
    {
    	$qb1 = $this->createQueryBuilder('p')
    	->select('MAX(p.participationDate)');
    	$lastParticipationDate = $qb1->getQuery()->getOneOrNullResult();

    	$qb2 = $this->createQueryBuilder('p')
    	->select('p')
    	->where('p.participationDate = :lastParticipationDate')
    	->setParameter('lastParticipationDate', $lastParticipationDate);

    	return $qb2->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Classs $class
     * @param Quarter $quarter
     * @return array
     */
    public function getNbParticipationByClassAndQuarter($class, $quarter)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) AS nbPart')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->andWhere('p.participationDate BETWEEN :start AND :end')
            ->setParameter('start', $quarter->getStartDate())
            ->setParameter('end', $quarter->getEndDate())
            ->leftJoin('p.student', 's')
            ->groupBy('p.student');

        return $qb->getQuery()->getResult();
    }

    public function getParticipationsByStudentAndQuarter($student, $quarter)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.participationDate BETWEEN :start AND :end')
            ->setParameter('start', $quarter->getStartDate())
            ->setParameter('end', $quarter->getEndDate())
            ->andWhere('p.student = :student')
            ->setParameter('student', $student);

        return $qb->getQuery()->getResult();
    }

}
