<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Service\ClassService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        } //TODO CG déplacer home
        $result = $service->getClass($classId);

        $students = $result['students'];
        $class = $result['class'];

        $student = new Student();
        $form = $this->createFormBuilder($student)
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('class', EntityType::class, [
                'class'=>'AppBundle:Classs',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('island', EntityType::class, [
                'class'=>'AppBundle:Island',
                'attr' => ['class' => 'form-control']
            ])
            ->add('phoneNumber', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->getForm();
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
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/student/{student}")
     */
    public function showStudentAction($student, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->getCompleteStudent($student)[0];
        $student['nbInterventions'] = count($student['interventions']);

        return $this->render('/dashboard/showStudentModal.html.twig', [
            'student' => $student
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
}