<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LivreController extends AbstractController
{
    #[Route('/catalogue', name: 'app_catalogue', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
            'livres' => $this->getLivres()
        ]);
    }

    #[Route('/livre/{id}', name: 'app_livre_id', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function getBookDataById(int $id): Response {

        $livre = array_values(array_filter($this->getLivres(), function ($item) use ($id) {
            return $item['id'] === $id;
        }))[0] ?? null;

        if (!$livre) {
            throw $this->createNotFoundException("Book not found");
        }

        return $this->render('livre/id.html.twig', [
            'controller_name' => 'LivreController',
            'livre' => $this->getLivres()[$id],
        ]);
    }

    #[Route('/catalogue/genre/{genre}', name: 'app_livre_genre', requirements: ['genre' => '\w+'], methods: ['GET'])]
    public function filterByGenre(string $genre): Response {
        $genredBooks = [];
        foreach ($this->getLivres() as $livre) {
            if ($livre['genre'] === $genre) {
                $genredBooks[] = $livre;
            }
        }

        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
            'livres' => $genredBooks,
        ]);
    }

    #[Route('api/catalogue', name: 'api_catalogue', methods: ['GET'])]
    public function getAllBooks(): JsonResponse {
        return new JsonResponse($this->getLivres());
    }

    #[Route('/statistiques', name: 'app_statistiques', methods: ['GET'])]
    public function getStatistiques() : Response {
        $livres = $this->getLivres();
        $totalLivres = count($livres);

        // Genre
        $genres = [];
        foreach ($livres as $livre) {
            if (isset($genres[$livre['genre']])) {
                $genres[$livre['genre']] += 1;
            } else {
                $genres[$livre['genre']] = 1;
            }
        }

        // Disponibles vs empruntés
        $disponibles = 0;
        $empruntes = 0;
        foreach ($livres as $livre) {
            if ($livre['disponible']) {
                $disponibles += $livre['nombre_exemplaires'];
            } else {
                $empruntes += 1;
            }
        }

        $auteurs = [];

        foreach ($livres as $livre) {
            $auteur = $livre['auteur'];
            if (isset($auteurs[$auteur])) {
                $auteurs[$auteur] += 1;
            } else {
                $auteurs[$auteur] = 1;
            }
        }

        $maxCount = 0;
        $auteurTop = null;
        foreach ($auteurs as $auteur => $count) {
            if ($count > $maxCount) {
                $maxCount = $count;
                $auteurTop = $auteur;
            }
        }

        return $this->render('livre/statistiques.html.twig', [
            'totalLivres' => $totalLivres,
            'genres' => $genres,
            'disponibles' => $disponibles,
            'empruntes' => $empruntes,
            'auteurTop' => $auteurTop,
        ]);
    }

    private function getLivres(): array {
        $livres = [
            1 => [
                'id' => 1,
                'titre' => 'Introduction aux Algorithmes',
                'auteur' => 'Thomas H. Cormen',
                'isbn' => '978-2100545261',
                'genre' => 'informatique',
                'annee_publication' => 2010,
                'nombre_pages' => 1200,
                'disponible' => true,
                'nombre_exemplaires' => 3,
                'resume' => 'Manuel de référence couvrant les algorithmes fondamentaux et les',
                'editeur' => 'Dunod',
                'cote' => 'INF.004.COR'
            ],
            2 => [
                'id' => 2,
                'titre' => 'Le Rouge et le Noir',
                'auteur' => 'Stendhal',
                'isbn' => '978-2070360024',
                'genre' => 'litterature',
                'annee_publication' => 1830,
                'nombre_pages' => 720,
                'disponible' => false,
                'nombre_exemplaires' => 0,
                'resume' => 'Roman emblématique du XIXe siècle suivant les ambitions de Julien',
                'editeur' => 'Gallimard',
                'cote' => 'LIT.840.STE'
            ],
            3 => [
                'id' => 3,
                'titre' => 'Physique Quantique - Fondements et Applications',
                'auteur' => 'Michel Le Bellac',
                'isbn' => '978-2759807802',
                'genre' => 'sciences',
                'annee_publication' => 2013,
                'nombre_pages' => 450,
                'disponible' => true,
                'nombre_exemplaires' => 2,
                'resume' => 'Introduction moderne à la mécanique quantique avec applications p',
                'editeur' => 'EDP Sciences',
                'cote' => 'PHY.530.LEB'
            ]
        ];
        return $livres;
    }
}
