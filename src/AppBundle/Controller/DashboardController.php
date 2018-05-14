<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Service\ClassService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        return $this->render('dashboard/home.html.twig', ['classes' => $classes]);
    }

    /**
     * @Route("/class/{classLetter}")
     */
    public function showClassAction($classLetter, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $service = new ClassService($em);
        $result = $service->getClass($classLetter);

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
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($student);
            $em->flush();

            return $this->redirect('/dashboard/class/'.$classLetter);
        }

        return $this->render('/dashboard/class.html.twig', [
                'class' => $class,
                'students' => $students,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/student/{student}")
     */
    public function showStudentAction($student)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Student')->getCompleteStudent($student)[0];
        $student['nbInterventions'] = count($student['interventions']);

        return $this->render('/dashboard/showStudentModal.html.twig', [
            'student' => $student
        ]);
    }
}