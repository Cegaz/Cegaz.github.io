<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ClasssController extends Controller
{
    public function accessGranted()
    {
        $key = $this->container->getParameter('key');
        if (isset($_SESSION['password']) || ((isset($_POST['password']) AND $_POST['password'] == $key))) {
            $_SESSION['password'] = $key;
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Route("/home", name="homepage")
     * @Method({"POST", "GET"})
     */
    public function homeAction()
    {
        if ($this->accessGranted()){
            $em = $this->getDoctrine()->getManager();

            $classes = $em->getRepository('AppBundle:Classs')->findAll();

            return $this->render('default/home.html.twig', ['classes' => $classes]);
        } else {
            return $this->render('default/index.html.twig', ['error' => true]);
        }
    }

    /**
     * @Route("/class/{classLetter}/{sorting}", name="one-class-page")
     */
    public function oneClassAction($classLetter, $sorting = 'name'){
        if($this->accessGranted()){
            $em = $this->getDoctrine()->getManager();

            $class = $em->getRepository('AppBundle:Classs')->findOneByClassLetter($classLetter);

            $students = $em->getRepository('AppBundle:Student')->getStudentsByClassSorted($class, $sorting);

            return $this->render('default/class.html.twig', ['students' => $students, 'sorting' => $sorting, 'class' => $class, 'classL' => $class.classLetter]);
        } else {
            return $this->redirect('/');
        }

    }
}
