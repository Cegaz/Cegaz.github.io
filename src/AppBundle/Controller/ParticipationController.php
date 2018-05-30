<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Intervention;
use AppBundle\Service\ClassService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/participation")
 */
class ParticipationController extends Controller
{
    /**
     * @Route("/sort-by-{sorting}", name="homepage-participation-sorted")
     * @Route("/", name="homepage-participation")
     * $param $sorting string
     * @return RedirectResponse|Response
     */
    public function homeAction($sorting = 'name', SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $service = new ClassService($em);

        $classId = $session->get('classId');
        $result = $service->getClass($classId, $sorting);

        $students = $result['students'];

        $class = $result['class'];

        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        if ($sorting == 'island') {
            return $this->render('participation/islands.html.twig', ['students' => $students, 'sorting' => 'island', 'class' => $class, 'classes' => $classes]);
        } else {
            return $this->render('participation/class.html.twig', ['students' => $students, 'sorting' => $sorting, 'class' => $class, 'classes' => $classes]);
        }

    }

//    /**
//     * @Route("/class/{classLetter}/{sorting}", name="one-class-page")
//     * @param $classLetter string
//     * @param $sorting string
//     * @return RedirectResponse|Response
//     */
//    public function oneClassAction($classLetter, $sorting = 'name'){
//        $em = $this->getDoctrine()->getManager();
//        $service = new ClassService($em);
//        $result = $service->getClass($classLetter, $sorting);
//
//        $students = $result['students'];
//        $class = $result['class'];
//
//        if($sorting == 'island') {
//            return $this->render('participation/islands.html.twig', ['students' => $students, 'sorting' => 'island', 'class' => $class]);
//        } else {
//            return $this->render('participation/class.html.twig', ['students' => $students, 'sorting' => $sorting, 'class' => $class]);
//        }
//    }

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

        //TODO CG ou directement setIntervention sur $student ?
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

    /**
     * @Route("/cancel", name="cancel-participation")
     * @return mixed
     */
    public function cancelLastAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lastInterventionDate = $em->getRepository('AppBundle:Intervention')->getLastIntervention()[1];
        $lastInterventionDateTime = new \DateTime($lastInterventionDate);
        $lastIntervention = $em->getRepository('AppBundle:Intervention')->findOneByInterventionDate($lastInterventionDateTime);

        $student = $lastIntervention->getStudent();
        $studentId = $student->getId();

        $nbInterventions = count($em->getRepository('AppBundle:Intervention')->findByStudent($studentId)) - 1;

        $em->remove($lastIntervention);
        $em->flush();

        $data = ['lastIntervention' => $lastIntervention,
            'studentId' => $studentId,
            'nbInterventions' => $nbInterventions];

        return new JsonResponse($data);
    }

}
