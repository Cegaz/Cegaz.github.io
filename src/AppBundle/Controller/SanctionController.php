<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 09/10/18
 * Time: 00:50
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Sanction;
use AppBundle\Service\SanctionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/sanction")
 */
class SanctionController extends Controller
{
    /**
     * @Route("/update")
     */
    public function updateSanction(Request $request)
    {
        $sanctionsId = $request->request->get('sanctionsId', []);

        $em = $this->getDoctrine()->getManager();

        foreach($sanctionsId as $sanctionId) {
            $sanction = $em->getRepository('AppBundle:Sanction')->find($sanctionId); /**@var Sanction $sanction */
            if (!empty($sanction)) $sanction->setDone(1);
            $em->flush();
        }

        if(!empty($sanction)) {
            $classId = $sanction->getStudent()->getClass();

            $sanctionsAlert = $em->getRepository('AppBundle:Sanction')->getSanctionsAlertsByClass($classId);
            usort($sanctionsAlert, function($a, $b) {
                /**@var Sanction $a */
                /**@var Sanction $b */
                return $a->getStudent()->getName() > $b->getStudent()->getName();
            });
        } else {
            $sanctionsAlert = [];
        }

        $html = $this->render('participation/showSanctionsModal.html.twig',
            ['sanctions' => $sanctionsAlert]);

        return $this->json(['html' => $html, 'nbSanctions' => count($sanctionsAlert)]);
    }
}