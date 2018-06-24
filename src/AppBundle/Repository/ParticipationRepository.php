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

    public function getNbParticipationByClassAndPeriod($class, $periodStart, $periodEnd)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) AS nbPart')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->andWhere('p.participationDate BETWEEN :start AND :end')
            ->setParameter('start', $periodStart)
            ->setParameter('end', $periodEnd)
            ->leftJoin('p.student', 's')
            ->groupBy('p.student');

        return $qb->getQuery()->getResult();
    }

    public function getParticipationsByStudentAndPeriod($student, $periodStart, $periodEnd)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.participationDate BETWEEN :start AND :end')
            ->setParameter('start', $periodStart)
            ->setParameter('end', $periodEnd)
            ->andWhere('p.student = :student')
            ->setParameter('student', $student);

        return $qb->getQuery()->getResult();
    }

}
