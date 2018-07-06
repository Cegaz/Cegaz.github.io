<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 08/05/18
 * Time: 23:01
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ChatController
 * @package AppBundle\Controller
 * @Route("/chat")
 */
class ChatController extends Controller
{
    /**
     * @Route("/")
     */
    public function homeAction()
    {
        return $this->render('chat/home.html.twig');
    }
}