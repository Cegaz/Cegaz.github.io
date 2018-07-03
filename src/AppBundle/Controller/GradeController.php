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
class GradeController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();

        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        $classId = $session->get('classId');
        // si pas de classeId définie en session, retour à homepage dashboard
        if(!isset($classId)) {
            return $this->render('default/home.html.twig', ['classes' => $classes]);
        }

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

    /**
     * @Route("/skills")
     */
    public function showSkillsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $skills = $em->getRepository('AppBundle:Skill')->findAll();

        return $this->render('/grades/showSkillsModal.html.twig', [
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
     * @Route("/calculate")
     */
    public function CalculateGradesByClass(SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $gradeService = new GradeService($em);

        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        $classId = $session->get('classId');
        // si pas de classeId définie en session, retour à homepage dashboard
        if(!isset($classId)) {
            return $this->render('default/home.html.twig', ['classes' => $classes]);
        }

        $gradeService->setGradesByClass($classId);

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
