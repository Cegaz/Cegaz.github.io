<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Island;
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
        $lastName = $request->request->get('lastName', 'none');
        $firstName = $request->request->get('firstName', 'none');
        $islandLabel = $request->request->get('islandLabel', 'none');
        $islandId = $request->request->get('islandId', 'none');

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
            if($lastName !== 'none' && $lastName !== '') {
                $student->setLastName($lastName);
            }
            if($firstName !== 'none' && $firstName !== '') {
                $student->setFirstName($firstName);
            }
            if($islandId !== 'none') {
                $island = $em->getRepository('AppBundle:Island')->find($islandId);
                $student->setIsland($island);
            }
            if($islandLabel !== 'none') {
                $island = new Island();
                $island->setLabel($islandLabel)
                    ->setClass($student->getClass());
                $em->persist($island);
                $em->flush();
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

        $data['studentName'] = ucfirst(strtolower($student->getFirstName())) . ' ' . strtoupper($student->getLastName());

        return new JsonResponse($data);
    }
}
