<?php

namespace App\DataFixtures;

use App\Entity\BestOnes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\OnlineCatalog;
use App\Entity\TvShow;
use App\Entity\Member;
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
        private static function tvshowDataGenerator1()
        {
                yield ["Game of Thrones", 2011, "David Benioff", 20];
                yield ["Peaky Blinders", 2013, "Steven Knight", 18];
                yield ["Vikings", 2013, "Helen Shaver", 17];
                yield ["Breaking Bad", 2008, "Brian Cranston, Vince Gilligan", 15];
                
        }


        private static function tvshowDataGenerator2()
        {
                yield ["Dark", 2017, "Baran bo Odar, Jantje Friese", 16];
                yield ["Ozark", 2017, "Bill Dubuque", 18];
                yield ["The Office", 2005, "Mindy Kaling", 16];
                
                
        }

        /**
         * Generates initialization data for members :
         *  [email, plain text password]
         * @return \\Generator
         */
        private function membersGenerator()
        {
                yield ['emilien@localhost','123456','ROLE_ADMIN'];
                yield ['maxence@localhost','123456','ROLE_USER'];
        }

        

        public function load(ObjectManager $manager)
        {
  
                $catalog = new OnlineCatalog();
                $bestone1 = new BestOnes();
                $bestone1->setDescription("Toute les séries");
                $bestone1->setPublished(true);


                foreach (self::tvshowDataGenerator1() as [$title, $year, $director, $note] ) {
                        $tvshow = new TvShow();
                        $tvshow->setName($title);
                        $tvshow->setYear($year);
                        $tvshow->setDirector($director);
                        $tvshow->setNote($note);
                        $manager->persist($tvshow);
                        $catalog->addTvshow($tvshow);
                        $bestone1->addTvshow($tvshow);
  
                }

        

                

                $catalog2= new OnlineCatalog();


                $bestone2 = new BestOnes();
                $bestone2->setDescription("Toute les séries");
                $bestone2->setPublished(false);


                $bestone3 = new BestOnes();
                $bestone3->setDescription("Humouristic");
                $bestone3->setPublished(true);

                foreach (self::tvshowDataGenerator2() as [$title, $year, $director, $note] ) {
                        $tvshow = new TvShow();
                        $tvshow->setName($title);
                        $tvshow->setYear($year);
                        $tvshow->setDirector($director);
                        $tvshow->setNote($note);
                        $manager->persist($tvshow);
                        $catalog2->addTvshow($tvshow);
                        $bestone2->addTvshow($tvshow);
  
                }

                
                

                

                // Création des membres (utilisateurs) via un générateur
                foreach ($this->membersGenerator() as [$email, $plainPassword,$role]) {
                        $user = new Member();
                        $password = $this->hasher->hashPassword($user, $plainPassword);
                        $user->setEmail($email);
                        $user->setPassword($password);
                        $roles = array();
                        $roles[] = $role;
                        $user->setRoles($roles);
                        $manager->persist($user);
                }

                // Enregistrement initial de tous les objets créés avant de les charger
                $manager->persist($catalog);
                $manager->persist($catalog2);
                $manager->flush(); // Sauvegarde initiale


                //Ajout d'une galerie publique humour pour 1 des utilisateurs
                $FilmRepo = $manager->getRepository(TvShow::class);
                $tvshow1 = $FilmRepo->findOneBy(['name' => "The Office"]);
                $bestone3->addTvshow($tvshow1);



                // Récupération des membres enregistrés par leur e-mail
                $MemberRepo = $manager->getRepository(Member::class);
                $user1 = $MemberRepo->findOneBy(['email' => 'emilien@localhost']);
                $user2 = $MemberRepo->findOneBy(['email' => 'maxence@localhost']);

                // Ajout d'une galerie = BestOne référencant tous les films déjà vus
                $bestone1->setCreator($user1);
                $bestone2->setCreator($user2);
                $bestone3->setCreator($user2);

                $manager->persist($bestone1);
                $manager->persist($bestone2);
                $manager->persist($bestone3);
                

                // Attribution des membres aux catalogues
                $catalog->setMember($user1);
                $catalog2->setMember($user2);

                // Finalisation de l'enregistrement
                $manager->flush();


        }


}
