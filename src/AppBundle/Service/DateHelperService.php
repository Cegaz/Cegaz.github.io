<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 30/04/18
 * Time: 22:18
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use DateTime;

class DateHelperService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    static function isToday(DateTime $dateTime)
    {
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        $dateTime->setTime( 0, 0, 0 );

        $diff = $today->diff($dateTime);
        $diffDays = (integer)$diff->format("%R%a");

        return (!$diffDays);
    }
}