<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SanctionReason;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/sanction-reason")
 */
class SanctionReasonController extends Controller
{
    /**
     * @Route("/add")
     */
    public function addSanctionReasonAction(Request $request)
    {
        $name = $request->request->get('name');

        $em = $this->getDoctrine()->getManager();

        $sanctionReason = new SanctionReason();
        $sanctionReason->setName($name);

        $em->persist($sanctionReason);
        $em->flush();

        $data['name'] = ucfirst(strtolower($name));
        $data['sanctionReasonId'] = $sanctionReason->getId();

        return new JsonResponse($data);
    }

}
