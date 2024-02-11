<?php
namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Competence;

use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('evaluation_level', ChoiceType::class, [
                'choices' => [
                    'Pas de connaissances' => 'pas_de_connaissances',
                    'Initier' => 'initier',
                    'Dessus de moyenne' => 'dessus_de_moy',
                    'Excellent' => 'excellent'
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
            'data_class' => Evaluation::class,
        ]);
    }
}
