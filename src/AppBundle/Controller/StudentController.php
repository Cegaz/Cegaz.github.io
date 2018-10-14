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
     * @Route("/show/{student}", name="show-student")
     */
    public function showStudentAction($student)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->getCompleteStudent($student)[0];
        $nbParticipations = count($student->getParticipations());

        return $this->render('/dashboard/showStudentModal.html.twig', [
            'student' => $student,
            'nbParticipations' => $nbParticipations
        ]);
    }

    /**
     * @Route("/modify")
     */
    public function modifyStudentAction(Request $request)
    {
        $idStudent = $request->request->get('idStudent');
        $email = $request->request->get('email', 'none');
        $phone = $request->request->get('phone', 'none');
        $name = $request->request->get('lastName', 'none');
        $surname = $request->request->get('surname', 'none');

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($idStudent);
        if (!empty($student)) {
            if($email !== 'none' &&
                $email !== "pas d'email enregistré" &&
                (empty($email) || preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email))) {
                $student->setEmail($email);
            }
            if($phone !== 'none' &&
                $phone !== 'pas de numéro de tél enregistré' &&
                preg_match('/^0[1-79][\s\.-]?([0-9][\s\.-]?){8}$/', $phone)) {
                $student->setPhoneNumber($phone);
            }
            if($name !== 'none' && $name !== '') {
                $student->setName($name);
            }
            if($surname !== 'none' && $surname !== '') {
                $student->setSurname($surname);
            }
            $em->flush();
            $success = true;
        } else {
            $success = false;
        }

        return new JsonResponse($success);
    }

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
