<?php

namespace AppBundle\Service;

use AppBundle\Entity\Classs;
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
     * @param array $file
     * @return mixed
     */
    public function importData($file)
    {
        if ($file['error'] == 2) {
            return 'too heavy'; //TODO CG gérer erreurs
        }
        $tmpFile = $file['tmp_name'];
        $fileUrl = 'assets/tmp/students-list' . date('Y-m-d h:i:s');
        if (!move_uploaded_file($tmpFile, $fileUrl)) {
            return 'transfer failed'; //TODO CG gérer erreurs
        }

        $spreadsheet = IOFactory::load($fileUrl);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $titles = array_flip($data[0]);

        for($i = 1; $i < count($data); $i++) {
            $student = new Student();

            $classLabel = $data[$i][$titles['CLASSE']];
            $classsRepository = $this->em->getRepository('AppBundle:Classs');
            $class = $classsRepository->findOneByLabel($classLabel);
            if (empty($class)) {
                $class = new Classs();
                $class->setLabel($classLabel);
                $this->em->persist($class);
                $this->em->flush();
            }

            if (isset($titles['ILOT'])) {
                $islandLabel = $data[$i][$titles['ILOT']];
                $islandRepository = $this->em->getRepository('AppBundle:Island');
                $island = $islandRepository->findOneByLabel($islandLabel);
                if (empty($island)) {
                    $island = new Island();
                    $island->setLabel($islandLabel)
                        ->setClass($class);
                    $this->em->persist($island);
                    $this->em->flush();
                }
                $student->setIsland($island);
            }

            $student
                ->setLastName($data[$i][$titles['NOM']])
                ->setFirstName($data[$i][$titles['PRENOM']])
                ->setClass($class);
            if (isset($titles['TEL'])) {
                $student->setPhoneNumber($data[$i][$titles['TEL']]);
            }
            if (isset($titles['MAIL'])) {
                $student->setEmail($data[$i][$titles['MAIL']]);
            }
            $this->em->persist($student);
        }

        $this->em->flush();
        unlink($fileUrl);
        //TODO CG prévoir retour success/errors
    }
}