<?php

namespace App\Form;

use App\Entity\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2, 'max' => 100]),
            ],
        ])
        ->add('lastName', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 2, 'max' => 100]),
            ],
        ])
        ->add('email', EmailType::class, [
            'constraints' => [
                new NotBlank(),
            ],
        ])
        ->add('phone', TelType::class, [
            'constraints' => [
                new NotBlank(),
                new Regex([
                    'pattern' => '/^\+?\d{10,15}$/',
                    'message' => 'Numéro de téléphone invalide.',
                ]),
            ],
        ])
        ->add('message', TextareaType::class, [
            'constraints' => [
                new NotBlank(),
                new Length(['min' => 10]),
            ],
        ])
        ->add('rgpd', CheckboxType::class, [
            'constraints' => [
                new NotBlank(),
            ],
            'label' => 'J\'accepte que mes données soient utilisées pour le traitement de ma demande.',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRequest::class,
            'csrf_protection' => false,
        ]);
    }
}
