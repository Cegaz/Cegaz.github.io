<?php

namespace AppBundle\Service;

use AppBundle\Entity\Grade;
use Doctrine\ORM\EntityManager;

class GradeService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $classId
     */
    public function setGradesByClass($classId)
    {
        $participationRepository = $this->em->getRepository('AppBundle:Participation');
        $absenceRepository = $this->em->getRepository('AppBundle:Absence');

        $quarters = $this->em->getRepository('AppBundle:Quarter')->findAll();

        foreach($quarters as $quarter) {
            $nbParticipations = $participationRepository
                ->getNbParticipationByClassAndQuarter($classId, $quarter);

            if(!empty($nbParticipations)) {
                $bestParticipation = (int)max($nbParticipations)['nbPart'];
                $nbSessions = count($participationRepository->getDistinctSessionsByQuarter($quarter, $classId));
                $bestParticipationPerSession = $bestParticipation/$nbSessions;

                $students = $this->em->getRepository('AppBundle:Student')->findByClass($classId);

                foreach ($students as $student) {
                    $participationsNb = count($participationRepository
                        ->getParticipationsByStudentAndQuarter($student, $quarter));
                    $nbSessionsAbsent = count($absenceRepository->getSessionsAbsentByStudentAndQuarter($student, $quarter));
                    $participationsPerSession = $participationsNb / ($nbSessions - $nbSessionsAbsent);

                    $calculatedGrade = $participationsPerSession / $bestParticipationPerSession * 20;

                    $grade = $this->em->getRepository('AppBundle:Grade')->findOneBy([
                        'student' => $student,
                        'quarter' => $quarter
                    ]);
                    if(empty($grade)) {
                        $grade = new Grade();
                        $grade->setStudent($student)
                            ->setAmount($calculatedGrade)
                            ->setQuarter($quarter);
                        $this->em->persist($grade);
                    } else {
                        $grade->setAmount($calculatedGrade);
                    }
                    $this->em->flush();
                }
            }
        }
    }
}