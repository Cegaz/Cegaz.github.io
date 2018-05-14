<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 08/05/18
 * Time: 23:01
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

/**
 * Class CommentController
 * @package AppBundle\Controller
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/add")
     */
    public function addCommentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $text = $request->request->get('text');
        $studentId = $request->request->get('studentId');
        $student = $em->getRepository('AppBundle:Student')->find($studentId);

        $comment = new Comment();
        $comment
            ->setText($text)
            ->setStudent($student)
            ->setCreationDate(new DateTime());

        $em->persist($comment);
        $em->flush();

        //TODO CG trouver plus propre !
        $data['text'] = $comment->getText();
        $data['date'] = $comment->getCreationDate()->format('d/m/Y');

        return new JsonResponse($data);
    }
}