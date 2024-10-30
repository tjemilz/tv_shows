<?php

namespace App\Form;

use App\Entity\BestOnes;
use App\Entity\OnlineCatalog;
use App\Entity\TvShow;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TvShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('year')
            ->add('director')
            ->add('note')
            ->add('onlineCatalog', null, [
                'disabled' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TvShow::class,
        ]);
    }
}
