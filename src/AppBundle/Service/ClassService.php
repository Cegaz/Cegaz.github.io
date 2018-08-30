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
            $student['nbParticipations'] = count($student['participations']);

            $student['nbParticipationsToday'] = 0;
            foreach($student['participations'] as $participation) {
                if(DateHelperService::isToday($participation['participationDate'])) {
                    $student['nbParticipationsToday'] ++;
                }
            }

            $dates = [];
            foreach($student['participations'] as $participation) {
                $dates[] = $participation['participationDate'];
            }
            if(!empty($dates)) {
                $student['lastParticipation'] = max($dates)->format('d/m');
            } else {
                $student['lastParticipation'] = '';
            }
        }

        if($sorting == 'nb-participations') {
            usort($students, function ($a, $b) {
                return (count($a['participations']) <= count($b['participations'])) ? -1 : 1;
            });
        } else if($sorting == 'last-participation') {
            usort($students, function ($a, $b) {
                return ($a['lastParticipation'] <= $b['lastParticipation']) ? -1 : 1;
            });
        } else if($sorting == 'nb-participations-today') {
            usort($students, function ($a, $b) {
                return ($a['nbParticipationsToday'] <= $b['nbParticipationsToday']) ? -1 : 1;
            });
        }

        return ['students' => $students, 'class' => $class];
    }
    
}