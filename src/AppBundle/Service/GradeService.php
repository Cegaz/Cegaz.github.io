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
        $quarters = $this->em->getRepository('AppBundle:Quarter')->findAll();

        foreach($quarters as $quarter) {
            $nbParticipations = $this->em->getRepository('AppBundle:Participation')->getNbParticipationByClassAndQuarter($classId, $quarter);

            if(!empty($nbParticipations)) {
                $bestParticipation = (int)max($nbParticipations)['nbPart'];

                $students = $this->em->getRepository('AppBundle:Student')->findByClass($classId);

                foreach ($students as $student) {
                    $participations = count($this->em->getRepository('AppBundle:Participation')->getParticipationsByStudentAndQuarter($student, $quarter));
                    $calculatedGrade = $participations / $bestParticipation * 20;

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