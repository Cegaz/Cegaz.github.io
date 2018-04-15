<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Intervention;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/participation")
 */
class ParticipationController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        return $this->render('participation/home.html.twig', ['classes' => $classes]);
    }

    /**
     * @Route("/class/{classLetter}/{sorting}", name="one-class-page")
     * @param $classLetter string
     * @param $sorting string
     * @return RedirectResponse|Response
     */
    public function oneClassAction($classLetter, $sorting = 'name'){
            $em = $this->getDoctrine()->getManager();
            $class = $em->getRepository('AppBundle:Classs')->findOneByClassLetter($classLetter);
            $students = $em->getRepository('AppBundle:Student')->getStudentsByClassSorted($class, $sorting);

            if($sorting == 'island') {
                return $this->render('participation/islands.html.twig', ['students' => $students, 'sorting' => 'island', 'class' => $class]);
            } else {
                return $this->render('participation/class.html.twig', ['students' => $students, 'sorting' => $sorting, 'class' => $class]);
            }
    }

    /**
     * @Route("/new", name="new-participation")
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $studentId = $request->request->get('student_id');
        $student = $em->getRepository('AppBundle:Student')->find($studentId);

        $intervention = new Intervention();
        $intervention
            ->setInterventionDate(new \DateTime('now'))
            ->setStudent($student);

        $em->persist($intervention);
        $em->flush();

        $interventions = $student->getInterventions();
        $data['nb'] = count($interventions);

        $lastCall = new \DateTime('01/01/1900');
        foreach($interventions as $intervention) {
            if ($intervention->getInterventionDate() > $lastCall) {
                $lastCall = $intervention->getInterventionDate();
            }
        }
        $data['lastCall'] = $lastCall->format('d/m');

        return new JsonResponse($data);
    }

}
