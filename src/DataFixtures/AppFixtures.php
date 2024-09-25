<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\OnlineCatalog;
use App\Entity\TvShow;
use App\Repository\OnlineCatalogRepository;
use App\Repository\TvShowRepository;



class AppFixtures extends Fixture
{
      /**
         * Generates initialization data for tv shows : [name, year, director, note]
         * @return \\Generator
         */
        private static function tvshowDataGenerator()
        {
                yield ["Game of Thrones", 2011, "David Benioff", 20];
                yield ["Peaky Blinders", 2013, "Steven Knight", 18];
                yield ["Vikings", 2013, "Helen Shaver", 17];
                yield ["Breaking Bad", 2008, "Brian Cranston, Vince Gilligan", 15];
                
        }

        

        public function load(ObjectManager $manager)
        {
                $tvshowRepo = $manager->getRepository(TvShow::class);
                $catalog = new OnlineCatalog();

                foreach (self::tvshowDataGenerator() as [$title, $year, $director, $note] ) {
                        $tvshow = new TvShow();
                        $tvshow->setName($title);
                        $tvshow->setYear($year);
                        $tvshow->setDirector($director);
                        $tvshow->setNote($note);
                        $manager->persist($tvshow);
                        
                        
                        $catalog->addTvshow($tvshow);
                        $manager->persist($catalog);

                }
                $manager->flush();



        }

        


}
