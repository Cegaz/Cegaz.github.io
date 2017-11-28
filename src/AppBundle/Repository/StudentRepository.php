<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class StudentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getStudentsByClassSorted($class, $sorting)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.id', 's.name', 's.surname')
            ->from('student', 's')
            ->join('intervention', 'i')
            ->where('s.class = ' . $class)
            ->orderBy($sorting, 'DESC')
            ->getQuery();

        return $qb->getResult();
    }

       /* $reponse = $bdd->prepare('SELECT s.id, UPPER(s.name) AS name_maj, LOWER(s.surname) AS surname_min, h.intervention
        FROM students s
        JOIN historical h
        ON s.id = h.id_student
        WHERE class = ? ORDER BY name'
                   );
        $reponse->execute(array($_GET['class']));
        }*/
}
