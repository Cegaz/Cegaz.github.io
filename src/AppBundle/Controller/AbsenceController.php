<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 14/09/18
 * Time: 23:56
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Absence;
use AppBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/absence")
 */
class AbsenceController extends Controller
{
    /**
     * @Route("/new")
     */
    public function newAbsence(Request $request, $date = '')
    {
        $em = $this->getDoctrine()->getManager();

        $studentId = $request->request->get('student_id');
        $student = $em->getRepository('AppBundle:Student')->find($studentId);
        $studentName = ucfirst($student->getSurname()) . ' ' . strtoupper($student->getName());

        $date = ($date == '') ? new \DateTime() : new \DateTime($date);
        $date = $date->setTime(0,0,0);

        $listAbsences = $em->getRepository('AppBundle:Absence')->findOneBy([
            'student' => $studentId,
            'date' => $date
        ]);
        if(!empty($listAbsences)) {
            return new JsonResponse(['success' => false, 'student' => $studentName, 'date' => $date->format('d/m/Y')]);
        }

        $absence = new Absence();
        $absence->setDate($date)
            ->setStudent($student);

        $em->persist($absence);
        $em->flush();

        return new JsonResponse(['success' => true, 'student' => $studentName, 'date' => $date->format('d/m')]);
    }
}