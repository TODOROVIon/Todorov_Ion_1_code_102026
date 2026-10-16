<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Rating\CalculateAverageRating;                                  //interface pour calculer la moyenne des notes d'un jeu vidéo
use App\Rating\CountRatingsPerValue;                                    //interface pour compter le nombre de notes par valeur pour un jeu vidéo
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;             //aide a charger les fixtures dans le bon ordre, obligatoire pour charger les fixtures de review après les fixtures de user et videoGame
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private CalculateAverageRating $calculateAverageRating,
        private CountRatingsPerValue $countRatingsPerValue)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $reviewComments = [
            "Très beau travail",
            "J'aime beaucoup ça",
            "C'est vraiment magnifique",
            "Super idée franchement",
            "Bravo pour ce résultat",
            "C'est très réussi",
            "Vraiment du beau travail",
            "J'adore ce style",
            "C'est juste incroyable",
            "Une très belle réalisation",
            "Franchement c'est top",
            "Le résultat est superbe",
            "J'aime beaucoup le rendu",
            "C'est vraiment impressionnant",
            "Continue comme ça"
            ];

        $faker = Factory::create('fr_FR');

        $videoGames = $manager->getRepository(VideoGame::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($videoGames as $videoGame) {
            $randomNrReviews = $faker->numberBetween(1, 4);
            $selecterUser = $faker->randomElements($users, min($randomNrReviews, count($users)));
            
            foreach ($selecterUser as $user) {
                $review = new Review();
                $review->setComment($faker->randomElement($reviewComments));
                $review->setRating($faker->numberBetween(1, 10));
                $review->setVideoGame($videoGame);
                $review->setUser($user);

                $manager->persist($review);
            }

            $this->calculateAverageRating->calculateAverage($videoGame);
            $this->countRatingsPerValue->countRatingsPerValue($videoGame);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            VideoGameFixtures::class,
        ];
    }
}
