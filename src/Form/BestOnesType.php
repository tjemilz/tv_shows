<?php

namespace App\Form;

use App\Entity\TvShow;
use App\Entity\BestOnes;
use App\Repository\TvShowRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BestOnesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupère le membre via l'option 'data'
        $bestones = $options['data'] ?? null;
        $member = $bestones->getCreator(); 

        $builder
            ->add('description')
            ->add('published')
            ->add('creator', null, [
                'disabled' => true,])
            ->add('tvshows', EntityType::class,
            [
                // avec 'by_reference' => false, sauvegarde les modifications
                'by_reference' => false,
                // classe pas obligatoire
                'class' => TvShow::class,
                // permet sélection multiple
                'multiple' => true,
                // affiche sous forme de checkboxes
                'expanded' => true,
                'query_builder' => function (TvShowRepository $er) use ($member) {
                                      return $er->createQueryBuilder('o')
                                                ->leftJoin('o.onlineCatalog', 'i')
                                                ->leftJoin('i.member', 'm')
                                                ->andWhere('m.id = :memberId')
                                                ->setParameter('memberId', $member->getId())
                                                ;}
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BestOnes::class,
        ]);
    }
}
