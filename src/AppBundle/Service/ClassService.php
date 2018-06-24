<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 30/04/18
 * Time: 22:18
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class ClassService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $classId
     * @param string $sorting
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function getClass($classId, $sorting = 'name')
    {
        $class = $this->em->getRepository('AppBundle:Classs')->find($classId);
        $students = $this->em->getRepository('AppBundle:Student')->getStudentsByClassSorted($class, $sorting);

        foreach($students as &$student) {
            $student['nbInterventions'] = count($student['interventions']);
            $dates = [];
            foreach($student['interventions'] as $intervention) {
                $dates[] = $intervention['interventionDate'];
            }
            if(!empty($dates)) {
                $student['lastIntervention'] = max($dates)->format('d/m');
            } else {
                $student['lastIntervention'] = '';
            }
        }

        if($sorting == 'nb-participations') {
            usort($students, function ($a, $b) {
                return (count($a['interventions']) <= count($b['interventions'])) ? -1 : 1;
            });
        } else if($sorting == 'last-participation') {
            usort($students, function ($a, $b) {
                return ($a['lastIntervention'] >= $b['lastIntervention']) ? -1 : 1;
            });
        }

        return ['students' => $students, 'class' => $class];
    }
    
}