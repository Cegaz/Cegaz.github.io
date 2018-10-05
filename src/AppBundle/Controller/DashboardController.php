<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sanction;
use AppBundle\Entity\SanctionReason;
use AppBundle\Entity\Student;
use AppBundle\Service\ClassService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="homepage-dashboard")
     * @return RedirectResponse|Response
     */
    public function homeAction(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $service = new ClassService($em);

        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        $classId = $session->get('classId');
        // si pas de classeId définie en session, retour à homepage dashboard
        if(!isset($classId)) {
            return $this->render('default/home.html.twig', ['classes' => $classes]);
        }
        $result = $service->getClass($classId);

        $students = $result['students'];
        $class = $result['class'];

        $skills = $em->getRepository('AppBundle:Skill')->findAll();
        $sanctions = $em->getRepository('AppBundle:SanctionReason')->findAll();

        $student = new Student();
        $form = $this->createForm('AppBundle\Form\StudentType', $student, ['class' => $class]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($student);
            $em->flush();

            return $this->redirect('/dashboard/class/'.$classId);
        }

        return $this->render('/dashboard/class.html.twig', [
                'class' => $class,
                'classes' => $classes,
                'students' => $students,
                'skills' => $skills,
                'sanctions' => $sanctions,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/student/{student}", name="show-student")
     */
    public function showStudentAction($student, Request $request)
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
     * @Route("/delete-student")
     */
    public function deleteStudentAction(Request $request)
    {
        $idStudent = $request->request->get('idStudent');

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($idStudent);

        $em->remove($student);
        $em->flush();
    }

    /**
     * @Route("/modify-student")
     */
    public function modifyStudentAction(Request $request)
    {
        $idStudent = $request->request->get('idStudent');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');

        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->find($idStudent);
        if (!empty($student)) {
            if($email != "pas d'email enregistré" &&
                (empty($email) || preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email))) {
                $student->setEmail($email);
            }
            if($phone != 'pas de numéro de tél enregistré' &&
                preg_match('/^0[1-79][\s\.-]?([0-9][\s\.-]?){8}$/', $phone)) {
                $student->setPhoneNumber($phone);
            }
            $em->flush();
            $success = true;
        } else {
            $success = false;
        }

        return new JsonResponse($success);
    }

    /**
     * @Route("/add-sanction-reason")
     */
    public function addSanctionReasonAction(Request $request)
    {
        $name = $request->request->get('name');

        $em = $this->getDoctrine()->getManager();

        $sanctionReason = new SanctionReason();
        $sanctionReason->setName($name);

        $em->persist($sanctionReason);
        $em->flush();

        $data['name'] = ucfirst(strtolower($name));
        $data['sanctionReasonId'] = $sanctionReason->getId();

        return new JsonResponse($data);
    }

}