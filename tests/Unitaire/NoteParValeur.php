<?php

declare(strict_types=1);

namespace App\Tests\Unitaire;

use PHPUnit\Framework\TestCase;
use App\Rating\RatingHandler;
use App\Model\Entity\VideoGame;
use App\Model\Entity\Review;
use App\Model\Entity\User;

class NoteParValeur extends TestCase
{

    public static function dataProviderNotes(): array
    {
        return [
            'aucun note' => [
                'notes' => [],
                'attendu' => [
                    'one' => 0,
                    'two' => 0,
                    'three' => 0,
                    'four' => 0,
                    'five' => 0
                ]
        ],
            'une note de 1' => [
                'notes' => [1],
                'attendu' => [
                    'one' => 1,
                    'two' => 0,
                    'three' => 0,
                    'four' => 0,
                    'five' => 0
                ]
            ],
            'une note de 2' => [
                'notes' => [2],
                'attendu' => [
                    'one' => 0,
                    'two' => 1,
                    'three' => 0,
                    'four' => 0,
                    'five' => 0
                ]
            ],
            'une note de 3' => [
                'notes' => [3],
                'attendu' => [
                    'one' => 0,
                    'two' => 0,
                    'three' => 1,
                    'four' => 0,
                    'five' => 0
                ]
            ],
            'une note de 4' => [
                'notes' => [4],
                'attendu' => [
                    'one' => 0,
                    'two' => 0,
                    'three' => 0,
                    'four' => 1,
                    'five' => 0
                ]
            ],
            'une note de 5' => [
                'notes' => [5],
                'attendu' => [
                    'one' => 0,
                    'two' => 0,
                    'three' => 0,
                    'four' => 0,
                    'five' => 1
                ]
            ],

            'plusieurs notes' => [
                'notes' => [1, 2, 3, 4, 5],
                'attendu' => [
                    'one' => 1,
                    'two' => 1,
                    'three' => 1,
                    'four' => 1,
                    'five' => 1
                ]
            ],

            'plusieurs notes avec doublons' => [
                'notes' => [1, 1, 2, 3, 3, 4, 5, 5],
                'attendu' => [
                    'one' => 2,
                    'two' => 1,
                    'three' => 2,
                    'four' => 1,
                    'five' => 2
                ]
            ],
        ];
    }
    
  public function testJeuxVide(): void
    {
        $videoGame = new VideoGame();
        $calculator = new RatingHandler();
    
        $calculator->countRatingsPerValue($videoGame);

        $compteur = $videoGame->getNumberOfRatingsPerValue();
    
        $this->assertSame(0, $compteur->getNumberOfOne());
        $this->assertSame(0, $compteur->getNumberOfTwo());
        $this->assertSame(0, $compteur->getNumberOfThree());
        $this->assertSame(0, $compteur->getNumberOfFour());
        $this->assertSame(0, $compteur->getNumberOfFive());
    }

    /**
     * @dataProvider dataProviderNotes
    */
    public function testJeuxAvecNotes(array $notes, array $attendu): void
    {
        $videoGame = new VideoGame();
        $user = new User();

        foreach ($notes as $note) {                 //$notes et $attendu depuis dataProviderNotes
            $review = new Review();
            $review->setRating($note);
            $review->setUser($user);
            $review->setVideoGame($videoGame);
            $videoGame->getReviews()->add($review);
        }

        $calculator = new RatingHandler();             //nouveau calculator
    
        $calculator->countRatingsPerValue($videoGame);  // l'execution de comptage par veleur de note
 
        $compteur = $videoGame->getNumberOfRatingsPerValue();           // depuis entité VideoGame, on récupère le compteur de note par valeur
    
        $this->assertSame($attendu['one'], $compteur->getNumberOfOne());
        $this->assertSame($attendu['two'], $compteur->getNumberOfTwo());
        $this->assertSame($attendu['three'], $compteur->getNumberOfThree());
        $this->assertSame($attendu['four'], $compteur->getNumberOfFour());
        $this->assertSame($attendu['five'], $compteur->getNumberOfFive());
    }

    public function testJeuxMauvaisNote(): void
    {
        $videoGame = new VideoGame();
        $user = new User();

        for($i = 0; $i < 5; $i++){
            $review = new Review();
            $review->setRating(1);
            $review->setUser($user);
            $review->setVideoGame($videoGame);
            $videoGame->getReviews()->add($review);
        }

        $calculator = new RatingHandler();
    
        $calculator->countRatingsPerValue($videoGame);

        $compteur = $videoGame->getNumberOfRatingsPerValue();
    
        $this->assertSame(5, $compteur->getNumberOfOne());
        $this->assertSame(0, $compteur->getNumberOfTwo());
        $this->assertSame(0, $compteur->getNumberOfThree());
        $this->assertSame(0, $compteur->getNumberOfFour());
        $this->assertSame(0, $compteur->getNumberOfFive());
    }

}