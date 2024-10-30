<?php

namespace App\Form;

use App\Entity\BestOnes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BestOnesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('published')
            ->add('creator', null, [
                'disabled' => true,])
            ->add('tvshows')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BestOnes::class,
        ]);
    }
}
