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
    public function indexAction(Request $request)
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

}
