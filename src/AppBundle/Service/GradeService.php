<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Classs;

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
            $periodStart = $quarter->getStartDate();
            $periodEnd = $quarter->getEndDate();
            $periodLabel = $quarter->getLabel();

            $nbParticipations = $this->em->getRepository('AppBundle:Participation')->getNbParticipationByClassAndPeriod($classId, $periodStart, $periodEnd);

            if(!empty($nbParticipations)) {
                $bestParticipation = (int)max($nbParticipations)['nbPart'];

                $students = $this->em->getRepository('AppBundle:Student')->findByClass($classId);

                foreach ($students as $student) {
                    $participations = count($this->em->getRepository('AppBundle:Participation')->getParticipationsByStudentAndPeriod($student, $periodStart, $periodEnd));
                    $grade = $participations / $bestParticipation * 20;

                    // TODO code provisoire en attendant gérer autrement les notes
                    switch ($periodLabel) {
                        case 'T1':
                            $student->setGradeT1($grade);
                            break;
                        case 'T2':
                            $student->setGradeT2($grade);
                            break;
                        case 'T3':
                            $student->setGradeT3($grade);
                            break;
                    }
                    $this->em->flush();
                }
            }
        }
    }
}