<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SanctionReason;
use AppBundle\Entity\Student;
use AppBundle\Service\ClassService;
use AppBundle\Service\FileService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
        $sanctionReasons = $em->getRepository('AppBundle:SanctionReason')->findAll();

        $student = new Student();
        $form = $this->createForm('AppBundle\Form\StudentType', $student, ['class' => $class]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($student);
            $em->flush();

            return $this->redirect('/dashboard');
        }

        return $this->render('/dashboard/class.html.twig', [
                'class' => $class,
                'classes' => $classes,
                'students' => $students,
                'skills' => $skills,
                'sanctionReasons' => $sanctionReasons,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/load")
     */
    public function loadFile()
    {
        $file = $_FILES['uploadedFile'];

        $em = $this->getDoctrine()->getManager();
        $service = new FileService($em);
        $service->importData($file);

        return $this->redirectToRoute("homepage-dashboard"); //TODO CG prévoir message retour
    }

}