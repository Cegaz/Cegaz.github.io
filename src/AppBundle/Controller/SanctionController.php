<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 09/10/18
 * Time: 00:50
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Sanction;
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
        $sanctionsId = $request->request->get('sanctionsId');

        $em = $this->getDoctrine()->getManager();

        foreach($sanctionsId as $sanctionId) {
            $sanction = $em->getRepository('AppBundle:Sanction')->find($sanctionId); /**@var Sanction $sanction */
            $sanction->setDone(1);
            $em->flush();
        }

        return new JsonResponse(['sanctionsIdUpdated' => $sanctionsId]);
    }
}