<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;
use App\Form\PlaylistType;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author Fish
 */
class AdminPlaylistsController extends AbstractController {
   
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
   
    /**
     * 
     * @param FormationRepository $repository
     */ 
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/admin/playlist/suppr/{id}', name: 'admin.playlist.suppr')]
    public function suppr(int $id): Response {
       $playlist = $this->playlistRepository->find($id);

    // Vérifiez si la playlist existe
    if (!$playlist) {
        return $this->redirectToRoute('admin.playlists', [
            'error' => 'Playlist non trouvée.'
        ]);
    }

    // Vérifiez s'il y a des formations associées à cette playlist
    $formations = $this->formationRepository->findBy(['playlist' => $playlist]);

    if (!empty($formations)) {
        return $this->redirectToRoute('admin.playlists', [
            'error' => 'La playlist ne peut pas être supprimée car elle est associée à des formations.'
        ]);
    }

    // Si aucune formation n'est associée, supprimez la playlist
    $this->playlistRepository->remove($playlist);

    return $this->redirectToRoute('admin.playlists');
    }
    
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response {
    
    $playlist = $this->playlistRepository->find($id);

    $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
    
    $formPlaylist->handleRequest($request);
    if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
        $this->playlistRepository->add($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);

    return $this->render("admin/admin.playlist.edit.html.twig", [
        'playlist' => $playlist,
        'formplaylist' => $formPlaylist->createView(),
        'playlistformations' => $playlistFormations,
    ]);
    }
    
    #[Route('/admin/playlist/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response {
    $playlist = new Playlist();

    $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

    $formPlaylist->handleRequest($request);
    if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
        $this->playlistRepository->add($playlist);
        return $this->redirectToRoute('admin.playlists'); 
    }

    return $this->render("admin/admin.playlist.ajout.html.twig", [
        'formplaylist' => $formPlaylist->createView(),
    ]);
    }

    
}
