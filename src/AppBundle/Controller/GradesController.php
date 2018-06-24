<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Skill;
use AppBundle\Service\ClassService;
use AppBundle\Service\GradeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/grades")
 */
class GradesController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $skills = $em->getRepository('AppBundle:Skill')->findAll();

        return $this->render('grades/home.html.twig', [
            'skills' => $skills
        ]);
    }

    /**
     * @Route("/add-skill")
     */
    public function addSkillAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $label = $request->request->get('label');

        $skill = new Skill();
        $skill->setLabel($label);

        $em->persist($skill);
        $em->flush();

        $data['label'] = $label;
        $data['skillId'] = $skill->getId();

        return new JsonResponse($data);
    }

     /**
     * @Route("/class")
     */
    public function calculateGradesByClass(SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $gradeService = new GradeService($em);

        $classId = $session->get('classId');
        // si pas de classeId définie en session, retour à homepage dashboard
        if(!isset($classId)) {
            return $this->render('default/home.html.twig', ['classes' => $classes]);
        }

        $gradeService->setGradesByClass($classId);

        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        $classService = new ClassService($em);

        $result = $classService->getClass($classId);
        $students = $result['students'];
        $class = $result['class'];

        return $this->render('grades/class.html.twig', [
            'classes' => $classes,
            'students' => $students,
            'class' => $class
        ]);
    }

}
