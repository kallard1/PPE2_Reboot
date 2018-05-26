<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('businessName', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.businessName',
                ],
            ])
            ->add('companyRegister', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.companyRegister',
                    'maxlength' => 14
                ],
            ])
            ->add('vatNumber', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.vatNumber',
                    'maxlength' => 13
                ],
            ])
            ->add('addresses', CollectionType::class, array(
                'entry_type' => AddressCustomerType::class,
                'entry_options' => array('label' => false),
                'prototype_name' => 'address__name__',
                'allow_add' => false,
                'allow_delete' => false,
                'prototype' => true,
                'by_reference' => false
            ))
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.username',
                    'maxlength' => 45
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'class' => 'password-field',
                    ],
                ],
                'required' => true,
                'first_options' => [
                    'attr' => [
                        'placeholder' => 'registration.form.password',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'registration.form.confirm_password',
                    ],
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.email',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
