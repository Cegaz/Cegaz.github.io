<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participation;
use AppBundle\Service\ClassService;
use AppBundle\Service\DateHelperService;
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
     * @param $sorting string
     * @return RedirectResponse|Response
     */
    public function homeAction($sorting = 'name', SessionInterface $session)
    {
        $classId = null;
        $em = $this->getDoctrine()->getManager();
        $service = new ClassService($em);

        $classes = $em->getRepository('AppBundle:Classs')->findAll();

        $user = $this->getUser();
        $userRoles = $user->getRoles();
        // si role étudiant définition automatique du classId
        if(in_array('ROLE_STUDENT', $userRoles)) {
            $classId = $user->getClassId();
        } else {
            $classId = $session->get('classId');
        // si pas de classeId définie en session, retour à homepage participation
            if(!isset($classId)) {
                return $this->render('default/home.html.twig', ['classes' => $classes]);
            }
        }

        $result = $service->getClass($classId, $sorting);

        $students = $result['students'];
        $class = $result['class'];

        return $this->render('participation/class.html.twig', ['students' => $students, 'sorting' => $sorting, 'class' => $class, 'classes' => $classes]);
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

        //TODO CG ou directement setParticipation sur $student ?
        $participation = new Participation();
        $participation
            ->setParticipationDate(new \DateTime('now'))
            ->setStudent($student);

        $em->persist($participation);
        $em->flush();

        $participations = $student->getParticipations();
        $data['nb'] = count($participations);

        $lastParticipation = new \DateTime('01/01/1900');
        $data['nbToday'] = 0;
        foreach($participations as $participation) {
            $participationDate = $participation->getParticipationDate();
            if ($participationDate > $lastParticipation) {
                $lastParticipation = $participationDate;
            }
            if(DateHelperService::isToday($participationDate)) {
                $data['nbToday'] ++;
            }
        }
        $data['lastParticipation'] = $lastParticipation->format('d/m');

        return new JsonResponse($data);
    }

    /**
     * @Route("/cancel", name="cancel-participation")
     * @return mixed
     */
    public function cancelLastAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lastParticipation = $em->getRepository('AppBundle:Participation')->getLastParticipation();

        // sécurité pour ne pas annuler des participations trop anciennes
        $oneHourAgo = new \DateTime("now");
        $oneHourAgo->sub(new \DateInterval('PT1H'));
        $participation = $lastParticipation->getParticipationDate();

        if($participation < $oneHourAgo) {
            return new JsonResponse(false);
        }

        $student = $lastParticipation->getStudent();
        $studentId = $student->getId();

        $nbParticipations = count($em->getRepository('AppBundle:Participation')->findByStudent($studentId)) - 1;

        $em->remove($lastParticipation);
        $em->flush();

        $participationsByStudent = $student->getParticipations()->toArray();
        $nbParticipationsToday = 0;

        if(!empty($participationsByStudent)) {
            $lastParticipationByStudent = max($participationsByStudent);
            $lastParticipationDateByStudent = $lastParticipationByStudent->getParticipationDate()->format('d/m');

            foreach($participationsByStudent as $participation) {
                if (DateHelperService::isToday($participation->getParticipationDate())) {
                    $nbParticipationsToday ++;
                }
            }
        } else {
            $lastParticipationDateByStudent = '';
        }

        $data = ['lastParticipation' => $lastParticipationDateByStudent,
            'studentId' => $studentId,
            'nbParticipations' => $nbParticipations,
            'nbParticipationsToday' => $nbParticipationsToday
        ];

        return new JsonResponse($data);
    }


}
