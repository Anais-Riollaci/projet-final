<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comments;

class CommentsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $comments = new Comments();
            $comments
                ->setUserId("Témoin n°$i")
                ->setAnimalId("Sujet Animal n°$i")
                ->setContent("<p>Contenu du témoignage n°$i</p>")
                ->setCreateAt(new \DateTime())
                ;
            $manager->persist($comments);
        }

        $manager->flush();
    }
}
