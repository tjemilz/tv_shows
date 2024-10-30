<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\OnlineCatalog;
use App\Entity\TvShow;
use App\Entity\Member;
use App\Repository\OnlineCatalogRepository;
use App\Repository\TvShowRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




class AppFixtures extends Fixture
{
        /**
         * Creates a function to hash passwords
         */

        private UserPasswordHasherInterface $hasher;

        public function __construct(UserPasswordHasherInterface $hasher)
        {
            $this->hasher = $hasher;
        }


      
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

        /**
         * Generates initialization data for members :
         *  [email, plain text password]
         * @return \\Generator
         */
        private function membersGenerator()
        {
                yield ['emilien@localhost','123456'];
                yield ['maxence@localhost','123456'];
        }

        

        public function load(ObjectManager $manager)
        {
  
                $catalog = new OnlineCatalog();


                foreach (self::tvshowDataGenerator() as [$title, $year, $director, $note] ) {
                        $tvshow = new TvShow();
                        $tvshow->setName($title);
                        $tvshow->setYear($year);
                        $tvshow->setDirector($director);
                        $tvshow->setNote($note);
                        $manager->persist($tvshow);
                        $catalog->addTvshow($tvshow);

                }



                $tvshow = new TvShow();
                $tvshow->setName("Dark");
                $tvshow->setYear(2017);
                $tvshow->setDirector("Baran bo Odar, Jantje Friese");
                $tvshow->setNote(16);
                $manager->persist($tvshow);

                $catalog2= new OnlineCatalog();
                $catalog2->addTvshow($tvshow);


                // Création des membres (utilisateurs) via un générateur
                foreach ($this->membersGenerator() as [$email, $plainPassword]) {
                        $user = new Member();
                        $password = $this->hasher->hashPassword($user, $plainPassword);
                        $user->setEmail($email);
                        $user->setPassword($password);
                        
                        $manager->persist($user);
                }

                // Enregistrement initial de tous les objets créés avant de les charger
                $manager->persist($catalog);
                $manager->persist($catalog2);
                $manager->flush(); // Sauvegarde initiale

                // Récupération des membres enregistrés par leur e-mail
                $MemberRepo = $manager->getRepository(Member::class);
                $user1 = $MemberRepo->findOneBy(['email' => 'emilien@localhost']);
                $user2 = $MemberRepo->findOneBy(['email' => 'maxence@localhost']);

                // Attribution des membres aux catalogues
                $catalog->setMember($user1);
                $catalog2->setMember($user2);

                // Finalisation de l'enregistrement
                $manager->flush();


        }


}
