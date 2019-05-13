<?php

namespace App\Form;

use App\Entity\Garden;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GardenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('address_specificities')
            ->add('meters')
            ->add('number_plots_row')
            ->add('number_plots_column')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Garden::class,
        ]);
    }
}
