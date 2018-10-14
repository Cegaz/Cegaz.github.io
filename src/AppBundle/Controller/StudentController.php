<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/student")
 */
class StudentController extends Controller
{
    /**
     * @Route("/delete")
     */
    public function deleteStudentAction(Request $request)
    {
        $studentId = $request->request->get('studentId');

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($studentId);
        /**@var $student Student */
        $student->setIsDeleted(true);
        $em->flush();

        $data['studentName'] = ucfirst(strtolower($student->getSurname())) . ' ' . strtoupper($student->getName());

        return new JsonResponse($data);
    }
}
