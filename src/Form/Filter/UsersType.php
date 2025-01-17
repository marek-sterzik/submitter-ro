<?php

namespace App\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UsersType extends AbstractType
{
    const TYPE_ALL = 'all';
    const TYPE_STUDENTS = 'students';
    const TYPE_TEACHERS = 'teachers';
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('t', ChoiceType::class, [
                'choices' => [
                    'všichni' => self::TYPE_ALL,
                    'studenti' => self::TYPE_STUDENTS,
                    'učitelé' => self::TYPE_TEACHERS,
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    "class" => "btn-group me-2 autosubmit",
                    "role" => "group",
                ],
                'label_attr' => ['class' => 'btn btn-outline-dark'],
                'choice_attr' => function ($choice, string $key, mixed $value) {
                    return ['class' => 'btn-check'];
                },
                "label" => false,
            ])
            ->add('a', CheckboxType::class, [
                "required" => false,
                "label" => "včetně původní role",
                'attr' => [
                    "class" => "btn-check autosubmit",
                ],
                "row_attr" => ["class" => "me-2"],
                'label_attr' => ['class' => 'btn btn-outline-secondary'],
            ])
            ->add('q', TextType::class, [
                "label" => false,
                "required" => false,
                "attr" => [
                    "placeholder" => "hledat uživatele...", 
                    "class" => "form-control"
                ],
                "row_attr" => ["class" => "me-2"],
            ])
            ->add('s', SubmitType::class, [
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
                "class" => "btn-toolbar justify-content-center mb-3 mt-4",
                "role" => "toolbar",
            ],
        ]);
    }
}
