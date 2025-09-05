<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LivreController extends AbstractController
{
    private array $livres = [
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

    #[Route('/catalogue', name: 'app_livre')]
    public function index(): Response
    {
        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
            'livres' => $this->livres
        ]);
    }

    #[Route('/livre/{id}', name: 'app_livre_id', requirements: ['page' => '\d+'])]
    public function getBookDataById(int $id): Response {
        $trouve = false;

        foreach ($this->livres as $livre) {
            if ($livre['id'] === $id) {
                $trouve = true;
            }
        }

        if (!$trouve) {
            throw $this->createNotFoundException();
        }

        return $this->render('livre/id.html.twig', [
            'controller_name' => 'LivreController',
            'livre' => $this->livres[$id],
        ]);
    }
}
