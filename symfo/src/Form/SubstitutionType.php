<?php

namespace App\Form;

use App\Entity\VacancySubstitute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SubstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class,[
                'label' => 'Du',
                'placeholder' => "Début du remplacement",
                'widget' => "single_text"
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Au',
                'placeholder' => "Fin du remplacement",
                'widget' => "single_text"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VacancySubstitute::class,
        ]);
    }
}
