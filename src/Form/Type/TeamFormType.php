<?php

// src/Form/Type/TaskType.php
namespace App\Form\Type;

use App\Form\Model\TeamDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('color', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamDto::class,
        ]);
    }

    // Estos dos metodos evitan que tenga que enviar el prefijo desde postman
    public function getBlockPrefix()
    {
        return "";
    }

    public function getName()
    {
        return "";
    }
}
