<?php

namespace App\Form;

use App\Entity\Vacancy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => "Début de l'absence",
                "widget" => "single_text"
            ])
            ->add('endDate', DateType::class, [
                'label' => "Fin de l'absence",
                "widget" => "single_text"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacancy::class,
        ]);
    }
}
