<?php

namespace App\Form;

use App\Entity\AddressCustomer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('line1', TextType::class, [
            'attr' => [
                'placeholder' => 'registration.form.addressLine1',
            ]
        ])
            ->add('line2', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'registration.form.addressLine2',
                ]
            ])
            ->add('postalCode', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.postalCode',
                    'maxlength' => 10
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.city',
                    'maxlength' => 50
                ]
            ])
            ->add('country', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.country',
                    'maxlength' => 75
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.phone',
                    'maxlength' => 15
                ]
            ])
            ->add('asDefault', CheckboxType::class, [
                'attr' => [
                    'placeholder' => 'registration.form.asDefault',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddressCustomer::class,
        ]);
    }
}
