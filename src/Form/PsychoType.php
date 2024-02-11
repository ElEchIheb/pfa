<?php
namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Psy;

use App\Entity\Psycho;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PsychoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('evaluation_level', ChoiceType::class, [
                'choices' => [
                    'Jugement difficile' => 'Jugement difficile',
                    'Point négatif' => 'Point négatif',
                    'a améliorer' => 'a améliorer',
                    'Point positif' => 'Point positif'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Psycho::class,
        ]);
    }
}
