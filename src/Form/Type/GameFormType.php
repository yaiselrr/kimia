<?php


namespace App\Form\Type;

use App\Entity\Team;
use App\Form\Model\GameDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPlayer', TextType::class)
            ->add('team', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameDto::class,
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
