<?php



namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdmInCategoriesController
 *
 * @author Fish
 */
class AdminCategoriesController extends AbstractController {
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @param CategorieRepository $categorierepository
     */
    public function __construct(CategorieRepository $categorierepository, FormationRepository $formationRepository) {
        $this->categorieRepository = $categorierepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response {
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories
        ]);
    }   

    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categorie.suppr')]
    public function suppr(int $id): Response {
    $categorie = $this->categorieRepository->find($id);
    
    if ($categorie) {
        // Vérifiez si des formations sont associées
        $formations = $categorie->getFormations(); // Récupérer les formations associées à cette catégorie

        if (count($formations) > 0) {
            $this->addFlash('error', 'Cette catégorie ne peut pas être supprimée car elle est rattachée à des formations.');
        } else {
            $this->categorieRepository->remove($categorie);
            $this->addFlash('success', 'Catégorie supprimée avec succès.');
        }
    } else {
        $this->addFlash('error', 'Catégorie non trouvée.');
    }

    return $this->redirectToRoute('admin.categories');
    }

    #[Route('/admin/categorie/ajout', name: 'admin.categorie.ajout')]
    public function ajout(Request $request): Response {
        $nameCategorie = $request->get("name");
    
    if ($nameCategorie) {
        $existingCategorie = $this->categorieRepository->findOneBy(['name' => $nameCategorie]);
        
        if ($existingCategorie) {
            $this->addFlash('error', 'Cette catégorie existe déjà.');
        } else {
            $categorie = new Categorie();
            $categorie->setName($nameCategorie);
            $this->categorieRepository->add($categorie);
            $this->addFlash('success', 'Catégorie ajoutée avec succès.');
        }
    } else {
        $this->addFlash('error', 'Le nom de la catégorie est requis.');
    }
    
    return $this->redirectToRoute('admin.categories');
    }
}
