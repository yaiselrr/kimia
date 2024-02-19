<?php


namespace App\Form\Type;

use App\Entity\Team;
use App\Form\Model\PlayerDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class PlayerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('position', TextType::class)
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'constraints' => [
                    new NotNull(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlayerDto::class,
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
