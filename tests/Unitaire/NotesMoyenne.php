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
    public function testJeu():void
    {
        $videoGame = new VideoGame();
        $calculator = new RatingHandler();

        $calculator->calculateAverage($videoGame);      //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)

        $this->assertNull($videoGame->getAverageRating());      // verifier si la valeur est null (assertNull)
    }

    public function testJeuNote5():void
    {
        $videoGame = new VideoGame();
        $user = new User();
        
        $review = new Review();
        $review->setRating(5);
        $review->setUser($user);
        $review->setVideoGame($videoGame);
        $videoGame->getReviews()->add($review);     //on recupere la collection de review puis on ajoute un review a la collection

        $calculator = new RatingHandler();

        $calculator->calculateAverage($videoGame);      //calculer la moyenne=average, src/Rating/RatingHandler(toutes les fonctionnes)

        $this->assertSame(5, $videoGame->getAverageRating());      // verifier si la valeur est 5 (assertSame)
    }

    public function testJeuNotes():void
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
        
        $this->assertSame(3, $videoGame->getAverageRating());      // verifier si la valeur est 3 (assertSame)
    }
    

}