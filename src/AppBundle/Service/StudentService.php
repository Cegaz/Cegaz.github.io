<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 30/04/18
 * Time: 22:18
 */

namespace AppBundle\Service;

use AppBundle\Entity\Student;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

class StudentService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isAbsentToday(Student $student)
    {
        $today = new \DateTime("now");
        $today = $today->setTime(0,0,0);

        $listAbsences = $this->em->getRepository('AppBundle:Absence')->findOneBy([
            'student' => $student->getId(),
            'date' => $today
        ]);

        return !empty($listAbsences);
    }

}