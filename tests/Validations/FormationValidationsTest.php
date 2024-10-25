<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author Fish
 */
class FormationValidationsTest extends KernelTestCase {
      public function getFormation(): Formation {
        return new Formation();
    }
  
    protected function assertErrors($entity, int $expectedErrorCount)
    {
    $errors = $this->getContainer()->get('validator')->validate($entity);
    $this->assertCount($expectedErrorCount, $errors);
    }

    
    public function testValidPublishedAt() {
    $formation = $this->getFormation()->setPublishedAt(new \DateTime('-1 day')); // Date valide (dans le passé)
    $this->assertErrors($formation, 0); // Aucune erreur attendue
    }

    public function testInvalidFuturePublishedAt() {
    $formation = (new Formation())->setPublishedAt(new \DateTime('+1 day'));
    $errors = $this->getContainer()->get('validator')->validate($formation);

    // Débogage des erreurs
    foreach ($errors as $error) {
        echo $error->getMessage() . "\n";
    }

    $this->assertErrors($formation, 0);
    }
    
}
