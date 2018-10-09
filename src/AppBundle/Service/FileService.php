<?php

namespace AppBundle\Service;

use AppBundle\Entity\Student;
use Doctrine\ORM\EntityManager;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $file
     * @return mixed
     */
    public function importData()
    {
        $fileName = 'importStudents.xlsx';
        $fileUrl = 'assets/doc/' . $fileName;
        $spreadsheet = IOFactory::load($fileUrl);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $titles = array_flip($data[0]);

        for($i = 1; $i < count($data); $i++) {
            $student = new Student();

            $classLabel = $data[$i][$titles['CLASSE']];
            $class = $this->em->getRepository('AppBundle:Classs')->findOneByLabel($classLabel);

            $student
                ->setName($data[$i][$titles['NOM']])
                ->setSurname($data[$i][$titles['PRENOM']])
                ->setPhoneNumber($data[$i][$titles['TEL']])
                ->setEmail($data[$i][$titles['MAIL']])
                ->setClass($class);

            $this->em->persist($student);
        }

        $this->em->flush();
    }
}