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
     * @Route("/", name="password-page")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/home", name="homepage")
     */
    public function homeAction()
    {
        return $this->render('default/home.html.twig');
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
     * @Route("/change-class", name="change-class")
     * @param SessionInterface $session
     */
    public function changeClassAction(Request $request, SessionInterface $session)
    {
        //TODO comprendre pourquoi ça marche pas...
        $class = $request->request->get('newClass');
        $session->set('class', $class);

        return new JsonResponse($class);
    }
}
