<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class TagFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $tagNames = ['Action','Adventure','RPG','Strategy','Simulation','Sports','Puzzle','Shooter','Fighting','Racing','Platformer','Horror','MMORPG','Indie','Casual'];

        foreach ($tagNames as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);

            $manager->persist($tag);
        }

        $manager->flush();
    
        
    }
}
