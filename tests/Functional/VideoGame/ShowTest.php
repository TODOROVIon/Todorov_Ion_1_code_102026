<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Tests\Functional\FunctionalTestCase;

final class ShowTest extends FunctionalTestCase
{
    public function testShouldShowVideoGame(): void
    {
        $this->get('/jeu-video-1');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Jeu vidéo 1');
    }

    public function testShouldAddReview(): void
    {
        $this->login('user+20@email.com');                       // connection en tant qu'utilisateur
        $crawler = $this->get('/jeu-video-10');                  //aller a la page du jeu vidéo
        
        self::assertResponseIsSuccessful();                     // confirmation que la réponse est réussie
        self::assertSelectorExists('form[name="review"]');      // confirmation que le formulaire de review est présent

        $this->client->submitForm('Poster', [                   // soumission du formulaire avec les données de l'avis
            'review[rating]' => '5',
            'review[comment]' => 'Super jeu !',
        ]);

        self::assertResponseRedirects('/jeu-video-10');          // confirmation que la réponse est une redirection vers la page du jeu vidéo
        $this->client->followRedirect();                        // suivre la redirection

        self::assertResponseIsSuccessful();                     // confirmation que la réponse est réussie après la redirection
        self::assertSelectorNotExists('form[name="review"]');   // confirmation que le formulaire de review n'est plus présent après la soumission

        $em = $this->getEntityManager();                        // récupération de l'EntityManager
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'user+20@email.com']);                        // récupération de l'utilisateur connecté depuis bdd
        $videoGame = $em->getRepository(VideoGame::class)->findOneBy(['slug' => 'jeu-video-10']);                    // récupération du jeu vidéo depuis bdd
        $review = $em->getRepository(Review::class)->findOneBy(['user' => $user, 'videoGame' => $videoGame]);       // recuperer l'avis 

        self::assertNotNull($review);                                   // confirmation que l'avis a été créé
        self::assertEquals(5, $review->getRating());                    // confirmation que la note de l'avis est correcte
        self::assertEquals('Super jeu !', $review->getComment());       // confirmation que le commentaire de l'avis est correct
    }

    public function testRedirectAfterSubmittingReview(): void
    {
        $this->login('user+9@email.com');                       // connection en tant qu'utilisateur (user enregistré 0-9, sinon erreur)
        $this->get('/jeu-video-12');                            // aller a la page du jeu vidéo

        self::assertResponseIsSuccessful();                     // confirmation que la réponse est réussie
        self::assertSelectorExists('form[name="review"]');      // confirmation que le formulaire de review est présent

        $this->client->submitForm('Poster', [                   // soumission du formulaire avec les données de l'avis
            'review[rating]' => '4',
            'review[comment]' => 'Bon jeu, mais il y a mieux !',
        ]);

        self::assertResponseStatusCodeSame(302);                    // confirmation que la réponse est une redirection(302)
        self::assertResponseRedirects('/jeu-video-12');              // confirmation que la redirection est vers la page du jeu vidéo
    }

    public function testReviewPresent(): void
    {
        $person='user+4@email.com';
        $game= 'jeu-video-15';
        $rating= 3;
        $comment= 'Moyen, pas mal mais pas top !';

        $this->login($person);                          // connection en tant qu'utilisateur (user enregistré 0-9, sinon erreur)
        $this->get("/{$game}");                         // aller a la page du jeu vidéo

        self::assertResponseIsSuccessful();                 // confirmation que la réponse est réussie

        self::assertSelectorExists('form[name="review"]');  // confirmation que le formulaire de review est présent
        
        $this->client->submitForm('Poster', [                   // soumission du formulaire avec les données de l'avis
            'review[rating]' => $rating,
            'review[comment]' => $comment,
        ]);
        
        self::assertResponseRedirects("/{$game}");              // confirmation que la réponse est une redirection vers la page du jeu vidéo
        $this->client->followRedirect();                        // suivre la redirection
        
        self::assertSelectorNotExists('form[name="review"]');   // confirmation que le formulaire de review est présent

        $em = $this->getEntityManager();                        // récupération de l'EntityManager
        $user = $em->getRepository(User::class)->findOneBy(['email' => $person]);                        // récupération de l'utilisateur connecté depuis bdd
        $review = $em->getRepository(Review::class)->findOneBy(['user' => $user, 'videoGame' => $em->getRepository(VideoGame::class)->findOneBy(['slug' => $game])]);       // recuperer l'avis

        self::assertNotNull($review);                                   // confirmation que l'avis existe toujours

        self::assertSame($rating, $review->getRating());                // confirmation que la   note de l'avis est correcte

        self::assertSame($comment, $review->getComment());              // confirmation que le commentaire de l'avis est correct

    }
}