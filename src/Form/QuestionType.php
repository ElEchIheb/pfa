<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questiontext')
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category', // Replace with the actual Category entity class
                'choice_label' => 'name', // Replace with the property in Category that represents the label to display
                'placeholder' => 'Select a category', // Optional placeholder text for the select box
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
