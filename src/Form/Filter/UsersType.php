<?php

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'všichni' => null,
                    'studenti' => 'students',
                    'učitelé' => 'teachers',
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    "class" => "btn-group me-2",
                    "role" => "group",
                ],
                'label_attr' => ['class' => 'btn btn-outline-dark'],
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    return ['class' => 'btn-check'];
                },
                "label" => false,
            ])
            ->add('query', TextType::class, [
                "label" => false,
                "attr" => ["placeholder" => "hledat uživatele...", "class" => "form-control"],
                "row_attr" => ["class" => "me-2"],
            ])
            ->add('submit', SubmitType::class, [
                "label" => "<i class=\"bi bi-search\"></i>",
                "label_html" => true,
                "attr" => ["class" => "btn btn-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "attr" => [
                "class" => "btn-toolbar justify-content-center mb-5",
                "role" => "toolbar",
            ],
        ]);
    }
}
