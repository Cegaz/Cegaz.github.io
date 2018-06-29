<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $_SESSION = [];
        session_destroy();
        return $this->redirect('/');
    }

    /**
     * @Route("/change-class")
     */
    public function changeClass(Request $request, SessionInterface $session)
    {
        $classId = $request->request->get('newClassId');
        $session->set('classId', $classId);

        return new JsonResponse($classId);
    }


    /**
     * @Route("/autocomplete", name="autocomplete")
     */
    public function getNamesList(Request $request)
    {
        $input = $request->get('term', null);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Student');

        if(!empty($input)) {
            $students = $repository->getStudentsByNameLike($input);
        } else {
            $students = $repository->findAll();
        }

        $list = [];
        foreach($students as $student) {
            $list[] = strtoupper($student->getName()) . ' ' . ucfirst($student->getSurname());
        }

        return new JsonResponse($list);
    }

    /**
     * @Route("/search")
     */
    public function searchStudents(Request $request)
    {
        $studentNameLike = $request->request->get('student_name_like');
        $students = $this->getDoctrine()->getManager()->getRepository('AppBundle:Student')->getStudentsByNameLike($studentNameLike);

        //TODO prévoir cas où plusieurs réponses
        if(!empty($students)) {
            $studentId = $students[0]->getId();
        } else {
            $studentId = 0;
        }

        return $this->redirectToRoute('show-student', ['student' => $studentId]);
    }

}
