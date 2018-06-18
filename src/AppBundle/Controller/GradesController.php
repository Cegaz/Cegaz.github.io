<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Skill;
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

}
