<?php

declare(strict_types=1);

namespace App\Tests\Unitaire;

use PHPUnit\Framework\TestCase;
use App\Rating\RatingHandler;
use App\Model\Entity\VideoGame;
use App\Model\Entity\Review;
use App\Model\Entity\User;

class NotesMoyenne extends TestCase
{
    public static function dataProviderNotes(): array
    {
        return [
            'aucune note' => [[], null],
            'une note' => [[5], 5],
            'plusieurs notes' => [[1, 3, 5], 3],
            'note 1' => [[1], 1],
            'note 2' => [[2], 2],
            'note 3' => [[3], 3],
            'note 4' => [[4], 4],
            'note 5' => [[5], 5],
            'plusieurs notes avec moyenne décimale' => [[1, 2, 3, 4, 5], 3],
        ];
    }

/* Test simple + test data provider */
    public function testJeux():void                             //Jeux sans note
    {
        $videoGame = new VideoGame();
        $calculator = new RatingHandler();

        $calculator->calculateAverage($videoGame);              //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)

        $this->assertNull($videoGame->getAverageRating());      // verifier si la valeur est null (assertNull)
    }
    
    /**
     * @dataProvider dataProviderNotes
     */
    public function test_jeux(array $notes, ?int $moyenneNote):void
    {
        $videoGame = new VideoGame();
        $user = new User();

        foreach ($notes as $note) {
            $review = new Review();
            $review->setRating($note);
            $review->setUser($user);
            $review->setVideoGame($videoGame);
            $videoGame->getReviews()->add($review);     //on recupere la collection de review puis on ajoute un review a la collection
        }

        $calculator = new RatingHandler();

        $calculator->calculateAverage($videoGame);      //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)

        $this->assertSame($moyenneNote, $videoGame->getAverageRating());      // verifier si la valeur est null (assertNull)
    }

    
/* Test notes 5 + test data provider */

    public function testJeuxNote5():void                            // jeux avec note 5 en dur
    {
        $videoGame = new VideoGame();
        $user = new User();
        
        $review = new Review();
        $review->setRating(5);
        $review->setUser($user);
        $review->setVideoGame($videoGame);
        $videoGame->getReviews()->add($review);                     //on recupere la collection de review puis on ajoute un review a la collection

        $calculator = new RatingHandler();

        $calculator->calculateAverage($videoGame);                  //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)

        $this->assertSame(5, $videoGame->getAverageRating());      // verifier si la valeur est 5 (assertSame)
    }
    
    /**
     * @dataProvider dataProviderNotes
     */
    public function test_jeuxNoteUnique(array $notes, ?int $moyenneNote):void
    {
        $videoGame = new VideoGame();
        $user = new User();
        
        foreach ($notes as $note) {
            $review = new Review();
            $review->setRating($note);
            $review->setUser($user);
            $review->setVideoGame($videoGame);
            $videoGame->getReviews()->add($review);     //on recupere la collection de review puis on ajoute un review a la collection
        }

        $calculator = new RatingHandler();

        $calculator->calculateAverage($videoGame);      //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)

        $this->assertSame($moyenneNote, $videoGame->getAverageRating());      // verifier si la valeur est 5 (assertSame)
    }
    public function test_jeuxNotes():void
    {
        $videoGame = new VideoGame();
        $user = new User();
        
        $review = new Review();
        $review->setRating(1);
        $review->setUser($user);
        $review->setVideoGame($videoGame);
        $videoGame->getReviews()->add($review);     //on recupere la collection de review puis on ajoute un review a la collection
        
        $review2 = new Review();
        $review2->setRating(3);
        $review2->setUser($user);
        $review2->setVideoGame($videoGame);
        $videoGame->getReviews()->add($review2);     //on recupere la collection de review puis on ajoute un review a la collection
        
        $review3 = new Review();
        $review3->setRating(5);
        $review3->setUser($user);
        $review3->setVideoGame($videoGame);
        $videoGame->getReviews()->add($review3);     //on recupere la collection de review puis on ajoute un review a la collection

        
        $calculator = new RatingHandler();
        
        $calculator->calculateAverage($videoGame);      //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)
        
        $this->assertSame(3, $videoGame->getAverageRating(), sprintf('votre note moyenne est de %d', 3));      // verifier si la valeur est 3 (assertSame)
    }

}