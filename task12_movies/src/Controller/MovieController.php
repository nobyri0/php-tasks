<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private array $movies = [
        [
            'id' => 1,
            'title' => 'Inception',
            'year' => 2010,
            'director' => 'Christopher Nolan',
            'description' => 'Профессиональный вор, крадущий корпоративные секреты через использование технологии вторжения в сны.',
            'rating' => 8.8,
            'genre' => 'Научная фантастика',
            'duration' => '148 мин'
        ],
        [
            'id' => 2,
            'title' => 'The Matrix',
            'year' => 1999,
            'director' => 'Wachowski Brothers',
            'description' => 'Программист Томас Андерсон ведет двойную жизнь хакера и узнает правду о мире.',
            'rating' => 8.7,
            'genre' => 'Научная фантастика',
            'duration' => '136 мин'
        ],
        [
            'id' => 3,
            'title' => 'Interstellar',
            'year' => 2014,
            'director' => 'Christopher Nolan',
            'description' => 'Группа астронавтов отправляется в космическое путешествие в поисках нового дома для человечества.',
            'rating' => 8.6,
            'genre' => 'Научная фантастика',
            'duration' => '169 мин'
        ],
        [
            'id' => 4,
            'title' => 'The Dark Knight',
            'year' => 2008,
            'director' => 'Christopher Nolan',
            'description' => 'Бэтмен пытается очистить Готэм от преступности, но появляется новый злодей - Джокер.',
            'rating' => 9.0,
            'genre' => 'Боевик',
            'duration' => '152 мин'
        ],
        [
            'id' => 5,
            'title' => 'Pulp Fiction',
            'year' => 1994,
            'director' => 'Quentin Tarantino',
            'description' => 'Криминальная драма, рассказывающая несколько переплетенных историй о преступниках.',
            'rating' => 8.9,
            'genre' => 'Криминал',
            'duration' => '154 мин'
        ]
    ];

    #[Route('/movies', name: 'movie_index')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $this->movies,
        ]);
    }

    #[Route('/movies/{id}', name: 'movie_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        // Находим фильм по ID
        $movie = null;
        foreach ($this->movies as $m) {
            if ($m['id'] === $id) {
                $movie = $m;
                break;
            }
        }

        if (!$movie) {
            throw $this->createNotFoundException('Фильм не найден');
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
