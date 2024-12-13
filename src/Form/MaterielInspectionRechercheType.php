<?php

namespace App\Form;

use App\Enum\Status;
use App\Tools\MaterielInspectionRecherche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielInspectionRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productCol', TextType::class, [
                'required' => false,
                'empty_data' => ''
            ])
            ->add('dateInstallDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('dateInstallFin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('dateInspectDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('dateInspectFin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('status', EnumType::class, [
                'class' => Status::class,
                'required' => false,
                'empty_data' => '',
            ])
            ->add('rechercher', SubmitType::class, [
                'label' => 'Rechercher',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaterielInspectionRecherche::class,
        ]);
    }
}
