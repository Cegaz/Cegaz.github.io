<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class InterventionRepository extends EntityRepository
{

    public function getLastIntervention()
    {
    	$qb1 = $this->createQueryBuilder('i')
    	->select('MAX(i.interventionDate)');
    	$lastInterventionDate = $qb1->getQuery()->getOneOrNullResult();

    	$qb2 = $this->createQueryBuilder('i')
    	->select('i')
    	->where('i.interventionDate = :lastInterventionDate')
    	->setParameter('lastInterventionDate', $lastInterventionDate);

    	return $qb2->getQuery()->getOneOrNullResult();
    }

    public function getNbParticipationByClassAndPeriod($class, $periodStart, $periodEnd)
    {
        $qb = $this->createQueryBuilder('i')
            ->select('COUNT(i.id) AS nbInt')
            ->where('s.class = :class')
            ->setParameter('class', $class)
            ->andWhere('i.interventionDate BETWEEN :start AND :end')
            ->setParameter('start', $periodStart)
            ->setParameter('end', $periodEnd)
            ->leftJoin('i.student', 's')
            ->groupBy('i.student');

        return $qb->getQuery()->getResult();
    }

    public function getInterventionsByStudentAndPeriod($student, $periodStart, $periodEnd)
    {
        //TODO CG faire le count ici ?
        $qb = $this->createQueryBuilder('i')
            ->select('i')
            ->where('i.interventionDate BETWEEN :start AND :end')
            ->setParameter('start', $periodStart)
            ->setParameter('end', $periodEnd)
            ->andWhere('i.student = :student')
            ->setParameter('student', $student);

        return $qb->getQuery()->getResult();
    }

}
