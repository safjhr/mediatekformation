<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationRepositoryTest
 *
 * @author Fish
 */
class FormationRepositoryTest extends KernelTestCase {
    public function recupRepository(): FormationRepository
    {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    public function testNbFormations()
    {
        $repository = $this->recupRepository();
        // Comptez le nombre de formations. Remplacez `3` par le nombre attendu dans votre base de données pour le test.
        $nbFormations = $repository->count([]);
        $this->assertEquals(2, $nbFormations);
    }
    
    public function newFormation(): Formation
    {
        $formation = (new Formation())
            ->setTitle("Eclipse n°25 : codage")
            ->setPublishedAt(new \DateTime("now"));
        return $formation;
    }

    public function testAddFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout de la formation.");
    }

    public function testRemoveFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "Erreur lors de la suppression de la formation.");        
    }

    public function testFindByContainValue()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "Eclipse n°25 : codage"); // Utiliser "title"
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations, "Aucune formation trouvée avec le titre spécifié.");
        $this->assertEquals("Eclipse n°25 : codage", $formations[0]->getTitle(), "Le titre de la formation trouvée ne correspond pas."); // Utiliser getTitle
    }
}
